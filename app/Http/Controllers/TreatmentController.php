<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\DentalChartEntry;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Prescription;
use App\Models\Service;
use App\Models\Treatment;
use App\Models\TreatmentDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $services = Service::where('is_active', true)->orderBy('name')->get(['id', 'name', 'price']);

        return view('treatments.index', compact('services'));
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
            'service_id' => $a->service_id,
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
            'services' => ['required', 'array', 'min:1'],
            'services.*.service_id' => ['required', 'exists:services,id'],
            'services.*.quantity' => ['required', 'integer', 'min:1'],
            'services.*.unit_price' => ['required', 'numeric', 'min:0'],
            'services.*.tooth_number' => ['nullable', 'string', 'max:10'],
        ], [
            'services.required' => 'Add at least one service performed.',
        ]);

        $appointment = Appointment::with(['service', 'patient'])->findOrFail($data['appointment_id']);
        $serviceNames = Service::whereIn('id', array_column($data['services'], 'service_id'))->pluck('name', 'id');

        [$treatment, $invoice] = DB::transaction(function () use ($data, $appointment, $serviceNames) {
            $treatment = Treatment::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'dentist_id' => $appointment->dentist_id,
                'visit_date' => $appointment->appointment_date,
                'diagnosis' => $data['diagnosis'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['services'] as $line) {
                TreatmentDetail::create([
                    'treatment_id' => $treatment->id,
                    'service_id' => $line['service_id'],
                    'tooth_number' => $line['tooth_number'] ?? null,
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                ]);
            }

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

            return [$treatment, $this->createInvoice($appointment, $data['services'], $serviceNames)];
        });

        ActivityLog::log('created', "Treatment recorded for {$appointment->patient->full_name}", $treatment);

        if (! $invoice) {
            return response()->json([
                'message' => 'Treatment recorded. This appointment already had an invoice, so no new invoice was created.',
            ], 201);
        }

        ActivityLog::log('created', "Invoice {$invoice->invoice_no} generated from treatment", $invoice);

        $canViewInvoices = collect(['admin', 'receptionist', 'accountant'])->contains(fn ($r) => Auth::user()->hasRole($r));

        return response()->json([
            'message' => "Treatment recorded and invoice {$invoice->invoice_no} generated.",
            'invoice_url' => $canViewInvoices ? route('invoices.show', $invoice) : null,
        ], 201);
    }

    /**
     * Bills the services performed as an unpaid invoice, unless the appointment is already invoiced.
     */
    protected function createInvoice(Appointment $appointment, array $lines, $serviceNames): ?Invoice
    {
        if ($appointment->invoice()->exists()) {
            return null;
        }

        $total = collect($lines)->sum(fn ($l) => $l['quantity'] * $l['unit_price']);

        $invoice = Invoice::create([
            'invoice_no' => Invoice::nextNumber(),
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'issue_date' => today(),
            'due_date' => today(),
            'subtotal' => $total,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => $total,
            'paid_amount' => 0,
            'status' => 'unpaid',
            'created_by' => Auth::id(),
        ]);

        foreach ($lines as $line) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'service_id' => $line['service_id'],
                'description' => $serviceNames[$line['service_id']] ?? 'Service',
                'tooth_number' => $line['tooth_number'] ?? null,
                'quantity' => $line['quantity'],
                'unit_price' => $line['unit_price'],
                'discount_amount' => 0,
                'tax_amount' => 0,
                'line_total' => $line['quantity'] * $line['unit_price'],
            ]);
        }

        return $invoice;
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
