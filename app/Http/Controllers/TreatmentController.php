<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\DentalChartEntry;
use App\Models\Prescription;
use App\Models\Treatment;
use App\Models\TreatmentDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TreatmentController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $query = Treatment::with(['patient', 'dentist.user'])->latest('visit_date');
            $total = Treatment::count();
            $treatments = $query->skip($request->get('start', 0))->take($request->get('length', 10))->get();

            return response()->json([
                'draw' => (int) $request->get('draw'),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $treatments->map(fn ($t) => [
                    'id' => $t->id,
                    'visit_date' => $t->visit_date->format('Y-m-d'),
                    'patient' => $t->patient->full_name,
                    'dentist' => $t->dentist->user->name ?? '-',
                    'diagnosis' => $t->diagnosis,
                ]),
            ]);
        }

        return view('treatments.index');
    }

    public function patientAppointments(Request $request): JsonResponse
    {
        $appointments = Appointment::with(['dentist.user', 'service'])
            ->where('patient_id', $request->get('patient_id'))
            ->where('status', 'completed')
            ->whereDoesntHave('treatment')
            ->orderByDesc('appointment_date')
            ->get();

        return response()->json($appointments->map(fn ($a) => [
            'id' => $a->id,
            'label' => $a->appointment_date->format('Y-m-d') . ' — ' . ($a->dentist->user->name ?? '-') . ' (' . ($a->service->name ?? '-') . ')',
        ]));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('treatments.index');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'appointment_id' => ['required', 'exists:appointments,id'],
            'diagnosis' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'tooth_conditions' => ['nullable', 'array'],
        ]);

        $appointment = Appointment::with('service')->findOrFail($data['appointment_id']);

        $treatment = DB::transaction(function () use ($data, $appointment) {
            $treatment = Treatment::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'dentist_id' => $appointment->dentist_id,
                'visit_date' => $appointment->appointment_date,
                'diagnosis' => $data['diagnosis'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            TreatmentDetail::create([
                'treatment_id' => $treatment->id,
                'service_id' => $appointment->service_id,
                'quantity' => 1,
                'unit_price' => $appointment->service->price ?? 0,
            ]);

            foreach ($data['tooth_conditions'] ?? [] as $tooth => $condition) {
                if ($condition) {
                    DentalChartEntry::create([
                        'patient_id' => $appointment->patient_id,
                        'treatment_id' => $treatment->id,
                        'tooth_number' => $tooth,
                        'condition' => $condition,
                        'recorded_date' => now(),
                    ]);
                }
            }

            return $treatment;
        });

        ActivityLog::log('created', "Treatment recorded for {$appointment->patient->full_name}", $treatment);

        return response()->json(['message' => 'Treatment recorded successfully.'], 201);
    }

    public function show(Treatment $treatment): View
    {
        $treatment->load(['patient', 'dentist.user', 'details.service', 'prescriptions', 'attachments']);

        return view('treatments.show', compact('treatment'));
    }

    public function storePrescription(Request $request, Treatment $treatment): JsonResponse
    {
        $data = $request->validate([
            'medicine_name' => ['required', 'string', 'max:150'],
            'dosage' => ['nullable', 'string', 'max:100'],
            'frequency' => ['nullable', 'string', 'max:100'],
            'duration' => ['nullable', 'string', 'max:100'],
            'instructions' => ['nullable', 'string'],
        ]);

        Prescription::create($data + [
            'treatment_id' => $treatment->id,
            'patient_id' => $treatment->patient_id,
            'dentist_id' => $treatment->dentist_id,
        ]);

        return response()->json(['message' => 'Prescription added successfully.'], 201);
    }

    public function destroy(Treatment $treatment): JsonResponse
    {
        $treatment->delete();
        ActivityLog::log('deleted', 'Treatment record deleted', $treatment);

        return response()->json(['message' => 'Treatment deleted successfully.']);
    }
}
