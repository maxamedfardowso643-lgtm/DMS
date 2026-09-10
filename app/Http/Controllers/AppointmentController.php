<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\AppointmentStatusLog;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $query = Appointment::with(['patient', 'dentist.user', 'service'])->latest('appointment_date');

            if ($search = $request->get('search')['value'] ?? null) {
                $query->where(function ($q) use ($search) {
                    $q->where('appointment_no', 'like', "%$search%")
                        ->orWhereHas('patient', fn ($p) => $p->where('first_name', 'like', "%$search%")->orWhere('last_name', 'like', "%$search%"));
                });
            }

            $total = Appointment::count();
            $filtered = $query->count();

            $appointments = $query->skip($request->get('start', 0))->take($request->get('length', 10))->get();

            return response()->json([
                'draw' => (int) $request->get('draw'),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered,
                'data' => $appointments->map(fn ($a) => [
                    'id' => $a->id,
                    'appointment_no' => $a->appointment_no,
                    'patient' => $a->patient->full_name,
                    'dentist' => $a->dentist->user->name ?? '-',
                    'service' => $a->service->name ?? '-',
                    'appointment_date' => $a->appointment_date->format('Y-m-d'),
                    'start_time' => substr($a->start_time, 0, 5),
                    'status' => $a->status,
                    'status_color' => $a->statusBadgeColor(),
                ]),
            ]);
        }

        $patients = Patient::orderBy('first_name')->get();
        $dentists = Dentist::with('user')->where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();

        return view('appointments.index', compact('patients', 'dentists', 'services'));
    }

    public function create(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('appointments.index');
    }

    public function calendarEvents(Request $request): JsonResponse
    {
        $appointments = Appointment::with(['patient', 'dentist.user', 'service'])
            ->whereBetween('appointment_date', [$request->get('start'), $request->get('end')])
            ->get();

        return response()->json($appointments->map(fn ($a) => [
            'id' => $a->id,
            'title' => "{$a->patient->full_name} - {$a->service->name}",
            'start' => "{$a->appointment_date->format('Y-m-d')}T{$a->start_time}",
            'end' => "{$a->appointment_date->format('Y-m-d')}T{$a->end_time}",
            'color' => match ($a->statusBadgeColor()) {
                'success' => '#28a745', 'danger' => '#dc3545', 'warning' => '#ffc107',
                'info' => '#17a2b8', 'primary' => '#007bff', 'dark' => '#343a40', default => '#6c757d',
            },
            'extendedProps' => ['status' => $a->status, 'dentist' => $a->dentist->user->name ?? ''],
        ]));
    }

    public function availableSlots(Request $request): JsonResponse
    {
        $request->validate([
            'dentist_id' => 'required|exists:dentists,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek;
        $service = Service::findOrFail($request->service_id);
        $duration = $service->duration_minutes;

        $onLeave = Schedule::where('dentist_id', $request->dentist_id)
            ->where('type', 'leave')->whereDate('leave_date', $date)->exists();

        if ($onLeave) {
            return response()->json(['slots' => [], 'message' => 'Dentist is on leave this day.']);
        }

        $schedule = Schedule::where('dentist_id', $request->dentist_id)
            ->where('type', 'weekly')->where('day_of_week', $dayOfWeek)->where('is_active', true)->first();

        if (! $schedule) {
            return response()->json(['slots' => [], 'message' => 'Dentist is not available on this day.']);
        }

        $existing = Appointment::where('dentist_id', $request->dentist_id)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->get(['start_time', 'end_time']);

        $slots = [];
        $cursor = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->start_time);
        $end = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->end_time);

        while ($cursor->copy()->addMinutes($duration)->lte($end)) {
            $slotStart = $cursor->copy();
            $slotEnd = $cursor->copy()->addMinutes($duration);

            $overlaps = $existing->contains(function ($apt) use ($slotStart, $slotEnd) {
                $aptStart = Carbon::parse($apt->start_time);
                $aptEnd = Carbon::parse($apt->end_time);
                return $slotStart->format('H:i') < $aptEnd->format('H:i') && $slotEnd->format('H:i') > $aptStart->format('H:i');
            });

            if (! $overlaps) {
                $slots[] = $slotStart->format('H:i');
            }

            $cursor->addMinutes($duration);
        }

        return response()->json(['slots' => $slots]);
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $service = Service::findOrFail($request->service_id);
        $start = Carbon::parse($request->start_time);
        $end = $start->copy()->addMinutes($service->duration_minutes);

        $conflict = Appointment::where('dentist_id', $request->dentist_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end->format('H:i:s'))
                    ->where('end_time', '>', $start->format('H:i:s'));
            })->exists();

        if ($conflict) {
            return response()->json(['message' => 'This dentist already has an appointment in that time slot.'], 422);
        }

        $appointment = DB::transaction(function () use ($request, $start, $end) {
            $appointment = Appointment::create([
                'appointment_no' => $this->nextNumber(),
                'patient_id' => $request->patient_id,
                'dentist_id' => $request->dentist_id,
                'service_id' => $request->service_id,
                'appointment_date' => $request->appointment_date,
                'start_time' => $start->format('H:i:s'),
                'end_time' => $end->format('H:i:s'),
                'source' => $request->source,
                'status' => 'booked',
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            AppointmentStatusLog::create([
                'appointment_id' => $appointment->id,
                'to_status' => 'booked',
                'changed_by' => Auth::id(),
                'remarks' => 'Appointment created',
            ]);

            return $appointment;
        });

        ActivityLog::log('created', "Appointment {$appointment->appointment_no} booked", $appointment);

        return response()->json(['message' => 'Appointment booked successfully.', 'appointment' => $appointment], 201);
    }

    protected function nextNumber(): string
    {
        $year = date('Y');
        $last = Appointment::where('appointment_no', 'like', "APT-$year-%")
            ->withTrashed()->orderByDesc('appointment_no')->value('appointment_no');
        $next = $last ? ((int) substr($last, -5)) + 1 : 1;

        return "APT-$year-" . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function show(Appointment $appointment): View
    {
        $appointment->load(['patient', 'dentist.user', 'service', 'statusLogs.changedBy']);

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment): JsonResponse
    {
        return response()->json($appointment);
    }

    public function update(StoreAppointmentRequest $request, Appointment $appointment): JsonResponse
    {
        $service = Service::findOrFail($request->service_id);
        $start = Carbon::parse($request->start_time);
        $end = $start->copy()->addMinutes($service->duration_minutes);

        $conflict = Appointment::where('dentist_id', $request->dentist_id)
            ->where('id', '!=', $appointment->id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end->format('H:i:s'))
                    ->where('end_time', '>', $start->format('H:i:s'));
            })->exists();

        if ($conflict) {
            return response()->json(['message' => 'This dentist already has an appointment in that time slot.'], 422);
        }

        $appointment->update([
            'patient_id' => $request->patient_id,
            'dentist_id' => $request->dentist_id,
            'service_id' => $request->service_id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'source' => $request->source,
            'notes' => $request->notes,
        ]);

        ActivityLog::log('updated', "Appointment {$appointment->appointment_no} updated", $appointment);

        return response()->json(['message' => 'Appointment updated successfully.', 'appointment' => $appointment]);
    }

    public function updateStatus(Request $request, Appointment $appointment): JsonResponse
    {
        $request->validate([
            'status' => ['required', Rule::in(Appointment::STATUSES)],
            'remarks' => ['nullable', 'string'],
        ]);

        $from = $appointment->status;
        $appointment->update([
            'status' => $request->status,
            'cancellation_reason' => $request->status === 'cancelled' ? $request->remarks : $appointment->cancellation_reason,
        ]);

        AppointmentStatusLog::create([
            'appointment_id' => $appointment->id,
            'from_status' => $from,
            'to_status' => $request->status,
            'changed_by' => Auth::id(),
            'remarks' => $request->remarks,
        ]);

        ActivityLog::log('status_changed', "Appointment {$appointment->appointment_no} status: $from -> {$request->status}", $appointment);

        return response()->json(['message' => 'Status updated successfully.', 'status' => $appointment->status, 'badge' => $appointment->statusBadgeColor()]);
    }

    public function reschedule(Request $request, Appointment $appointment): JsonResponse
    {
        $request->validate([
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
        ]);

        $duration = Carbon::parse($appointment->start_time)->diffInMinutes(Carbon::parse($appointment->end_time));
        $start = Carbon::parse($request->start_time);
        $end = $start->copy()->addMinutes($duration);

        $conflict = Appointment::where('dentist_id', $appointment->dentist_id)
            ->where('id', '!=', $appointment->id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end->format('H:i:s'))
                    ->where('end_time', '>', $start->format('H:i:s'));
            })->exists();

        if ($conflict) {
            return response()->json(['message' => 'Time slot conflicts with another appointment.'], 422);
        }

        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
        ]);

        AppointmentStatusLog::create([
            'appointment_id' => $appointment->id,
            'from_status' => $appointment->status,
            'to_status' => $appointment->status,
            'changed_by' => Auth::id(),
            'remarks' => 'Rescheduled via drag-and-drop',
        ]);

        return response()->json(['message' => 'Appointment rescheduled.']);
    }

    public function destroy(Appointment $appointment): JsonResponse
    {
        $appointment->delete();

        ActivityLog::log('deleted', "Appointment {$appointment->appointment_no} deleted", $appointment);

        return response()->json(['message' => 'Appointment deleted successfully.']);
    }

    public function myAppointments(): View
    {
        $patient = Auth::user()->patient;
        $appointments = $patient ? $patient->appointments()->with(['dentist.user', 'service'])->latest('appointment_date')->get() : collect();

        return view('appointments.my', compact('appointments'));
    }
}
