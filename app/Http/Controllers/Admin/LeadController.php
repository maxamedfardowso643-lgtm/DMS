<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\AppointmentRequest;
use App\Models\AppointmentStatusLog;
use App\Models\ContactMessage;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(): View
    {
        $bookingRequests = AppointmentRequest::with('service')->latest()->get();
        $contactMessages = ContactMessage::latest()->get();
        $testimonials = Testimonial::latest()->get();
        $dentists = Dentist::with('user')->where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();

        return view('admin.leads.index', compact('bookingRequests', 'contactMessages', 'testimonials', 'dentists', 'services'));
    }

    public function updateStatus(Request $request, AppointmentRequest $appointmentRequest): JsonResponse
    {
        $request->validate([
            'status' => ['required', Rule::in(['pending', 'contacted', 'converted', 'dismissed'])],
        ]);

        $appointmentRequest->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated.']);
    }

    public function convert(Request $request, AppointmentRequest $appointmentRequest): JsonResponse
    {
        $data = $request->validate([
            'patient_id' => ['nullable', 'exists:patients,id'],
            'first_name' => ['required_without:patient_id', 'nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['required_without:patient_id', 'nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'dentist_id' => ['required', 'exists:dentists,id'],
            'service_id' => ['required', 'exists:services,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string'],
        ]);

        $service = Service::findOrFail($data['service_id']);
        $start = Carbon::parse($data['start_time']);
        $end = $start->copy()->addMinutes($service->duration_minutes);

        $conflict = Appointment::where('dentist_id', $data['dentist_id'])
            ->whereDate('appointment_date', $data['appointment_date'])
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end->format('H:i:s'))
                    ->where('end_time', '>', $start->format('H:i:s'));
            })->exists();

        if ($conflict) {
            return response()->json(['message' => 'This dentist already has an appointment in that time slot.'], 422);
        }

        $appointment = DB::transaction(function () use ($data, $start, $end, $appointmentRequest) {
            $patient = isset($data['patient_id'])
                ? Patient::findOrFail($data['patient_id'])
                : Patient::create([
                    'patient_code' => $this->nextPatientCode(),
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'] ?? '',
                    'phone' => $data['phone'],
                    'email' => $data['email'] ?? null,
                    'is_active' => true,
                ]);

            $appointment = Appointment::create([
                'appointment_no' => $this->nextAppointmentNumber(),
                'patient_id' => $patient->id,
                'dentist_id' => $data['dentist_id'],
                'service_id' => $data['service_id'],
                'appointment_date' => $data['appointment_date'],
                'start_time' => $start->format('H:i:s'),
                'end_time' => $end->format('H:i:s'),
                'source' => 'scheduled',
                'status' => 'booked',
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            AppointmentStatusLog::create([
                'appointment_id' => $appointment->id,
                'to_status' => 'booked',
                'changed_by' => Auth::id(),
                'remarks' => 'Converted from website lead',
            ]);

            $appointmentRequest->update(['status' => 'converted']);

            return $appointment;
        });

        ActivityLog::log('created', "Appointment {$appointment->appointment_no} created from website lead", $appointment);

        return response()->json(['message' => 'Lead converted to appointment successfully.', 'appointment' => $appointment], 201);
    }

    protected function nextAppointmentNumber(): string
    {
        $year = date('Y');
        $last = Appointment::where('appointment_no', 'like', "APT-$year-%")
            ->withTrashed()->orderByDesc('appointment_no')->value('appointment_no');
        $next = $last ? ((int) substr($last, -5)) + 1 : 1;

        return "APT-$year-" . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    protected function nextPatientCode(): string
    {
        $year = date('Y');
        $last = Patient::where('patient_code', 'like', "PT-$year-%")
            ->withTrashed()->orderByDesc('patient_code')->value('patient_code');
        $next = $last ? ((int) substr($last, -5)) + 1 : 1;

        return "PT-$year-" . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function markMessageRead(ContactMessage $contactMessage): JsonResponse
    {
        $contactMessage->update(['is_read' => true]);

        return response()->json(['message' => 'Marked as read.']);
    }

    public function destroyMessage(ContactMessage $contactMessage): JsonResponse
    {
        $contactMessage->delete();

        return response()->json(['message' => 'Message deleted.']);
    }

    public function destroyRequest(AppointmentRequest $appointmentRequest): JsonResponse
    {
        $appointmentRequest->delete();

        return response()->json(['message' => 'Request deleted.']);
    }

    public function approveTestimonial(Testimonial $testimonial): JsonResponse
    {
        $testimonial->update(['is_approved' => true, 'is_read' => true]);

        return response()->json(['message' => 'Comment approved and will now show on the website.']);
    }

    public function unapproveTestimonial(Testimonial $testimonial): JsonResponse
    {
        $testimonial->update(['is_approved' => false]);

        return response()->json(['message' => 'Comment hidden from the website.']);
    }

    public function destroyTestimonial(Testimonial $testimonial): JsonResponse
    {
        $testimonial->delete();

        return response()->json(['message' => 'Comment deleted.']);
    }

    /**
     * Add the person who left a website comment into the Appointment Requests lead list.
     */
    public function addTestimonialAsLead(Testimonial $testimonial): JsonResponse
    {
        $appointmentRequest = AppointmentRequest::create([
            'name' => $testimonial->name,
            'phone' => $testimonial->phone ?? '',
            'email' => $testimonial->email,
            'notes' => "From website comment: \"{$testimonial->message}\"",
            'status' => 'pending',
        ]);

        $testimonial->update(['is_read' => true]);

        return response()->json(['message' => 'Added to Appointment Requests leads.', 'appointment_request' => $appointmentRequest], 201);
    }
}
