<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\AppointmentStatusLog;
use App\Models\Dentist;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $dentistId = $request->user()->scopedDentistId();
            $query = Appointment::with(['patient', 'dentist.user', 'service'])
                ->when($dentistId, fn ($q) => $q->where('dentist_id', $dentistId));

            if ($search = $request->get('search')['value'] ?? null) {
                $query->where(function ($q) use ($search) {
                    $q->where('appointment_no', 'like', "%$search%")
                        ->orWhereHas('patient', fn ($p) => $p->where('first_name', 'like', "%$search%")->orWhere('last_name', 'like', "%$search%"));
                });
            }

            $total = Appointment::when($dentistId, fn ($q) => $q->where('dentist_id', $dentistId))->count();
            $filtered = $query->count();

            $orderable = [0 => 'appointment_no', 4 => 'appointment_date', 5 => 'start_time', 6 => 'status'];
            $column = $orderable[(int) $request->input('order.0.column', 4)] ?? 'appointment_date';
            $direction = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
            $query->orderBy($column, $direction);
            if ($column === 'appointment_date') {
                $query->orderBy('start_time', $direction);
            }

            $appointments = $query->skip($request->get('start', 0))->take($request->get('length', 10))->get();

            return response()->json([
                'draw' => (int) $request->get('draw'),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered,
                'data' => $appointments->map(fn ($a) => [
                    'id' => $a->id,
                    'appointment_no' => $a->appointment_no,
                    'patient' => $a->patient->full_name ?? '-',
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
            ->whereBetween('appointment_date', [
                Carbon::parse($request->get('start'))->toDateString(),
                Carbon::parse($request->get('end'))->toDateString(),
            ])
            ->when($request->user()->scopedDentistId(), fn ($q, $id) => $q->where('dentist_id', $id))
            ->get();

        return response()->json($appointments->map(fn ($a) => [
            'id' => $a->id,
            'title' => ($a->patient->full_name ?? '-') . ' - ' . ($a->service->name ?? '-'),
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
        $duration = Service::findOrFail($request->service_id)->duration_minutes;

        if ($this->onLeave($request->dentist_id, $date)) {
            return response()->json(['slots' => [], 'message' => 'Dentist is on leave this day.']);
        }

        $shifts = $this->shifts($request->dentist_id, $date);

        if ($shifts->isEmpty()) {
            return response()->json(['slots' => [], 'message' => 'Dentist is not available on this day.']);
        }

        // Staff editing an appointment must still see its own current slot as free.
        $excludeId = $request->routeIs('appointments.available-slots') ? $request->integer('exclude') : 0;

        $existing = Appointment::where('dentist_id', $request->dentist_id)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->get(['start_time', 'end_time']);

        $slots = [];

        // A dentist can work several shifts on one day (e.g. morning and afternoon).
        foreach ($shifts as $shift) {
            $cursor = $this->startsAt($date, $shift->start_time);
            $shiftEnd = $this->startsAt($date, $shift->end_time);

            while ($cursor->copy()->addMinutes($duration)->lte($shiftEnd)) {
                $slotStart = $cursor->format('H:i');
                $slotEnd = $cursor->copy()->addMinutes($duration)->format('H:i');

                $overlaps = $existing->contains(fn ($apt) => $slotStart < substr($apt->end_time, 0, 5) && $slotEnd > substr($apt->start_time, 0, 5));

                if (! $overlaps && $cursor->isFuture()) {
                    $slots[] = $slotStart;
                }

                $cursor->addMinutes($duration);
            }
        }

        if (empty($slots)) {
            return response()->json(['slots' => [], 'message' => 'No free time slots left on this day.']);
        }

        return response()->json(['slots' => array_values(array_unique($slots))]);
    }

    protected function onLeave(int|string $dentistId, Carbon $date): bool
    {
        return Schedule::where('dentist_id', $dentistId)
            ->where('type', 'leave')->whereDate('leave_date', $date)->exists();
    }

    protected function shifts(int|string $dentistId, Carbon $date): Collection
    {
        return Schedule::where('dentist_id', $dentistId)
            ->where('type', 'weekly')->where('day_of_week', $date->dayOfWeek)->where('is_active', true)
            ->orderBy('start_time')->get();
    }

    /**
     * Appointment times are clinic-local; build the moment in that zone so
     * past/future checks are right even though the app runs in UTC.
     */
    protected function startsAt(Carbon|string $date, string $time): Carbon
    {
        return Carbon::parse(Carbon::parse($date)->format('Y-m-d') . ' ' . $time, config('app.clinic_timezone'));
    }

    /**
     * Why the dentist can't take an appointment at this time, or null if they can.
     */
    protected function slotError(int|string $dentistId, string $date, Carbon $start, Carbon $end, ?int $excludeId = null): ?string
    {
        $day = Carbon::parse($date);

        if ($this->startsAt($day, $start->format('H:i'))->isPast()) {
            return 'That time has already passed. Please pick a later slot.';
        }

        if ($this->onLeave($dentistId, $day)) {
            return 'Dentist is on leave this day.';
        }

        $fitsShift = $this->shifts($dentistId, $day)->contains(fn ($s) => $start->format('H:i') >= substr($s->start_time, 0, 5)
            && $end->format('H:i') <= substr($s->end_time, 0, 5));

        if (! $fitsShift) {
            return "That time is outside the dentist's working hours.";
        }

        if ($this->hasConflict((int) $dentistId, $date, $start, $end, $excludeId)) {
            return 'This dentist already has an appointment in that time slot.';
        }

        return null;
    }

    /**
     * Dentists only see their own appointments in the list; keep it that way
     * for direct links and actions too.
     */
    protected function ensureAccessible(Appointment $appointment): void
    {
        $dentistId = request()->user()->scopedDentistId();

        abort_if($dentistId && (int) $appointment->dentist_id !== $dentistId, 403);
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $service = Service::findOrFail($request->service_id);
        $start = Carbon::parse($request->start_time);
        $end = $start->copy()->addMinutes($service->duration_minutes);

        if ($error = $this->slotError($request->dentist_id, $request->appointment_date, $start, $end)) {
            return response()->json(['message' => $error], 422);
        }

        $appointment = $this->retryOnDuplicateNumber(fn () => DB::transaction(function () use ($request, $start, $end) {
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
        }));

        ActivityLog::log('created', "Appointment {$appointment->appointment_no} booked", $appointment);

        return response()->json(['message' => 'Appointment booked successfully.', 'appointment' => $appointment], 201);
    }

    /**
     * Two bookings saved at the same moment can compute the same appointment
     * number; retry so the second one picks the next free number.
     */
    protected function retryOnDuplicateNumber(callable $callback): mixed
    {
        return retry(3, $callback, 50, fn ($e) => $e instanceof UniqueConstraintViolationException);
    }

    protected function nextNumber(): string
    {
        $year = date('Y');
        $last = Appointment::where('appointment_no', 'like', "APT-$year-%")
            ->withTrashed()->orderByDesc('appointment_no')->value('appointment_no');
        $next = $last ? ((int) substr($last, -5)) + 1 : 1;

        return "APT-$year-" . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    protected function nextInvoiceNumber(): string
    {
        $year = date('Y');
        $last = Invoice::where('invoice_no', 'like', "INV-$year-%")
            ->withTrashed()->orderByDesc('invoice_no')->value('invoice_no');
        $next = $last ? ((int) substr($last, -5)) + 1 : 1;

        return "INV-$year-" . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    protected function hasConflict(int $dentistId, string $date, Carbon $start, Carbon $end, ?int $excludeId = null): bool
    {
        return Appointment::where('dentist_id', $dentistId)
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end->format('H:i:s'))
                    ->where('end_time', '>', $start->format('H:i:s'));
            })->exists();
    }

    public function show(Appointment $appointment): View
    {
        $this->ensureAccessible($appointment);

        $appointment->load(['patient', 'dentist.user', 'service', 'statusLogs.changedBy']);

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment): JsonResponse
    {
        $this->ensureAccessible($appointment);
        $appointment->load('patient');

        return response()->json([
            'id' => $appointment->id,
            'appointment_no' => $appointment->appointment_no,
            'patient_id' => $appointment->patient_id,
            'patient_name' => $appointment->patient->full_name ?? '-',
            'dentist_id' => $appointment->dentist_id,
            'service_id' => $appointment->service_id,
            'appointment_date' => $appointment->appointment_date->format('Y-m-d'),
            'start_time' => substr($appointment->start_time, 0, 5),
            'source' => $appointment->source,
            'notes' => $appointment->notes,
            'status' => $appointment->status,
        ]);
    }

    public function update(StoreAppointmentRequest $request, Appointment $appointment): JsonResponse
    {
        $this->ensureAccessible($appointment);

        $service = Service::findOrFail($request->service_id);
        $start = Carbon::parse($request->start_time);
        $end = $start->copy()->addMinutes($service->duration_minutes);

        // Only re-check the slot when it actually moves, so notes on a past
        // appointment can still be corrected.
        $slotChanged = (int) $request->dentist_id !== (int) $appointment->dentist_id
            || (int) $request->service_id !== (int) $appointment->service_id
            || Carbon::parse($request->appointment_date)->format('Y-m-d') !== $appointment->appointment_date->format('Y-m-d')
            || $start->format('H:i') !== substr($appointment->start_time, 0, 5);

        if ($slotChanged && $error = $this->slotError($request->dentist_id, $request->appointment_date, $start, $end, $appointment->id)) {
            return response()->json(['message' => $error], 422);
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
        $this->ensureAccessible($appointment);

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
        $this->ensureAccessible($appointment);

        if (in_array($appointment->status, ['completed', 'cancelled', 'no_show'])) {
            return response()->json(['message' => 'Only upcoming appointments can be rescheduled.'], 422);
        }

        $request->validate([
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
        ]);

        $duration = Carbon::parse($appointment->start_time)->diffInMinutes(Carbon::parse($appointment->end_time));
        $start = Carbon::parse($request->start_time);
        $end = $start->copy()->addMinutes($duration);

        if ($error = $this->slotError($appointment->dentist_id, $request->appointment_date, $start, $end, $appointment->id)) {
            return response()->json(['message' => $error], 422);
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
        $this->ensureAccessible($appointment);

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

    public function bookingForm(): View
    {
        $dentists = Dentist::with('user')->where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();

        return view('appointments.book', compact('dentists', 'services'));
    }

    public function storeMyBooking(Request $request): JsonResponse
    {
        $patient = Auth::user()->patient;

        if (! $patient) {
            return response()->json(['message' => 'No patient profile is linked to your account. Please contact the clinic.'], 422);
        }

        $request->validate([
            'dentist_id' => ['required', 'exists:dentists,id'],
            'service_id' => ['required', 'exists:services,id'],
            'appointment_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string'],
        ]);

        $service = Service::findOrFail($request->service_id);
        $start = Carbon::parse($request->start_time);
        $end = $start->copy()->addMinutes($service->duration_minutes);

        if ($error = $this->slotError($request->dentist_id, $request->appointment_date, $start, $end)) {
            return response()->json(['message' => $error], 422);
        }

        [$appointment, $invoice] = $this->retryOnDuplicateNumber(fn () => DB::transaction(function () use ($request, $start, $end, $service, $patient) {
            $appointment = Appointment::create([
                'appointment_no' => $this->nextNumber(),
                'patient_id' => $patient->id,
                'dentist_id' => $request->dentist_id,
                'service_id' => $request->service_id,
                'appointment_date' => $request->appointment_date,
                'start_time' => $start->format('H:i:s'),
                'end_time' => $end->format('H:i:s'),
                'source' => 'scheduled',
                'status' => 'booked',
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            AppointmentStatusLog::create([
                'appointment_id' => $appointment->id,
                'to_status' => 'booked',
                'changed_by' => Auth::id(),
                'remarks' => 'Appointment self-booked via patient portal',
            ]);

            $invoice = Invoice::create([
                'invoice_no' => $this->nextInvoiceNumber(),
                'appointment_id' => $appointment->id,
                'patient_id' => $patient->id,
                'issue_date' => now(),
                'due_date' => now(),
                'subtotal' => $service->price,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'total_amount' => $service->price,
                'paid_amount' => 0,
                'status' => 'unpaid',
                'notes' => "Auto-generated for appointment {$appointment->appointment_no}",
                'created_by' => Auth::id(),
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'service_id' => $service->id,
                'description' => $service->name,
                'quantity' => 1,
                'unit_price' => $service->price,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'line_total' => $service->price,
            ]);

            return [$appointment, $invoice];
        }));

        ActivityLog::log('created', "Appointment {$appointment->appointment_no} self-booked", $appointment);

        return response()->json([
            'message' => 'Appointment booked successfully. The fee has been added to your outstanding balance.',
            'appointment' => $appointment,
            'invoice_total' => (float) $invoice->total_amount,
            'redirect' => route('my-appointments'),
        ], 201);
    }

    public function cancelMyBooking(Request $request, Appointment $appointment): JsonResponse
    {
        $patient = Auth::user()->patient;

        abort_unless($patient && $appointment->patient_id === $patient->id, 403);

        if (! in_array($appointment->status, ['booked', 'confirmed'])) {
            return response()->json(['message' => 'Only booked or confirmed appointments can be cancelled.'], 422);
        }

        if ($this->startsAt($appointment->appointment_date, $appointment->start_time)->isPast()) {
            return response()->json(['message' => 'This appointment has already started or passed.'], 422);
        }

        $request->validate(['reason' => ['nullable', 'string', 'max:500']]);
        $reason = $request->reason ?: 'Cancelled by patient';
        $from = $appointment->status;

        DB::transaction(function () use ($appointment, $from, $reason) {
            $appointment->update(['status' => 'cancelled', 'cancellation_reason' => $reason]);

            AppointmentStatusLog::create([
                'appointment_id' => $appointment->id,
                'from_status' => $from,
                'to_status' => 'cancelled',
                'changed_by' => Auth::id(),
                'remarks' => $reason,
            ]);

            // Drop the auto-generated fee if nothing has been paid on it yet.
            $appointment->invoice()->where('paid_amount', 0)->whereIn('status', ['draft', 'unpaid'])
                ->update(['status' => 'cancelled']);
        });

        ActivityLog::log('status_changed', "Appointment {$appointment->appointment_no} cancelled by patient", $appointment);

        return response()->json(['message' => 'Your appointment has been cancelled.']);
    }
}
