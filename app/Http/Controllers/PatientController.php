<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Models\ActivityLog;
use App\Models\DentalChartEntry;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $query = Patient::query()->latest();

            if ($search = $request->get('search')['value'] ?? null) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('patient_code', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
            }

            $total = Patient::count();
            $filtered = $query->count();

            $patients = $query->skip($request->get('start', 0))
                ->take($request->get('length', 10))
                ->get();

            return response()->json([
                'draw' => (int) $request->get('draw'),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered,
                'data' => $patients->map(fn ($p) => [
                    'id' => $p->id,
                    'patient_code' => $p->patient_code,
                    'full_name' => $p->full_name,
                    'phone' => $p->phone,
                    'email' => $p->email,
                    'gender' => $p->gender,
                    'is_active' => $p->is_active,
                ]),
            ]);
        }

        return view('patients.index');
    }

    public function create(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('patients.index');
    }

    /**
     * Multi-field patient search used by Appointment / Invoice / Payment booking modals.
     * Matches name, patient code, phone, or emergency contact phone.
     */
    public function search(Request $request): JsonResponse
    {
        $term = trim((string) $request->get('q'));

        if ($term === '') {
            return response()->json([]);
        }

        $patients = Patient::where(function ($q) use ($term) {
            $q->where('first_name', 'like', "%$term%")
                ->orWhere('last_name', 'like', "%$term%")
                ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%$term%"])
                ->orWhere('patient_code', 'like', "%$term%")
                ->orWhere('phone', 'like', "%$term%")
                ->orWhere('emergency_contact_phone', 'like', "%$term%");
        })
            ->where('is_active', true)
            ->orderBy('first_name')
            ->limit(15)
            ->get();

        return response()->json($patients->map(fn ($p) => [
            'id' => $p->id,
            'name' => $p->full_name,
            'code' => $p->patient_code,
            'phone' => $p->phone,
            'emergency_contact_phone' => $p->emergency_contact_phone,
        ]));
    }

    public function ledger(Patient $patient): View
    {
        return view('patients.ledger', $this->buildLedgerData($patient));
    }

    public function ledgerModal(Patient $patient): View
    {
        return view('patients._ledger_modal', $this->buildLedgerData($patient));
    }

    protected function buildLedgerData(Patient $patient): array
    {
        $patient->load(['invoices.items', 'invoices.payments.paymentMethod']);

        $transactions = collect();

        foreach ($patient->invoices as $invoice) {
            foreach ($invoice->items as $item) {
                $transactions->push([
                    'date' => $invoice->issue_date,
                    'description' => 'Charge: ' . $item->description . ($item->discount_amount > 0 ? ' (Discount applied: $' . number_format($item->discount_amount, 2) . ')' : ''),
                    'due' => (float) $item->line_total,
                    'paid' => null,
                ]);
            }

            foreach ($invoice->payments as $payment) {
                $label = 'Payment via ' . $payment->method;
                if ($payment->sender_phone) {
                    $label .= ' — ' . $payment->sender_phone;
                }
                if ($payment->reference_no) {
                    $label .= ' (Ref: ' . $payment->reference_no . ')';
                }
                $transactions->push([
                    'date' => $payment->payment_date,
                    'description' => $label,
                    'due' => null,
                    'paid' => (float) $payment->amount,
                ]);
            }
        }

        $transactions = $transactions->sortBy('date')->values();

        $running = 0;
        $transactions = $transactions->map(function ($t) use (&$running) {
            $running += ($t['due'] ?? 0) - ($t['paid'] ?? 0);
            $t['balance'] = $running;
            return $t;
        });

        return [
            'patient' => $patient,
            'transactions' => $transactions,
            'totalDue' => $transactions->sum('due'),
            'totalPaid' => $transactions->sum('paid'),
        ];
    }

    public function quickView(Patient $patient): View
    {
        $patient->load([
            'appointments' => fn ($q) => $q->latest('appointment_date')->limit(5),
            'appointments.dentist.user',
            'appointments.service',
            'invoices',
            'dentalChartEntries',
        ]);

        return view('patients._quick_view', compact('patient'));
    }

    public function store(StorePatientRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['patient_code'] = $this->nextCode();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('patients', 'public');
        }

        $patient = Patient::create($data);

        ActivityLog::log('created', "Patient {$patient->full_name} registered", $patient);

        return response()->json(['message' => 'Patient registered successfully.', 'patient' => $patient], 201);
    }

    protected function nextCode(): string
    {
        $year = date('Y');
        $last = Patient::where('patient_code', 'like', "PT-$year-%")
            ->withTrashed()->orderByDesc('patient_code')->value('patient_code');
        $next = $last ? ((int) substr($last, -5)) + 1 : 1;

        return "PT-$year-" . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    public function show(Patient $patient): View
    {
        $patient->load(['appointments.dentist.user', 'appointments.service', 'invoices', 'dentalChartEntries', 'attachments']);

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient): JsonResponse
    {
        return response()->json($patient);
    }

    public function update(StorePatientRequest $request, Patient $patient): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('patients', 'public');
        } else {
            unset($data['photo']);
        }

        $patient->update($data);

        ActivityLog::log('updated', "Patient {$patient->full_name} updated", $patient);

        return response()->json(['message' => 'Patient updated successfully.', 'patient' => $patient]);
    }

    public function updateDentalChart(Request $request, Patient $patient): JsonResponse
    {
        $data = $request->validate([
            'tooth_conditions' => ['nullable', 'array'],
            'tooth_conditions.*' => ['string', 'in:healthy,decayed,filled,missing,crowned,root_canal,implant,extracted,impacted'],
        ]);

        foreach ($data['tooth_conditions'] ?? [] as $tooth => $condition) {
            DentalChartEntry::updateOrCreate(
                ['patient_id' => $patient->id, 'tooth_number' => $tooth, 'treatment_id' => null],
                ['condition' => $condition, 'recorded_date' => now()]
            );
        }

        ActivityLog::log('updated', "Dental chart updated for {$patient->full_name}", $patient);

        return response()->json(['message' => 'Dental chart updated successfully.']);
    }

    public function destroy(Patient $patient): JsonResponse
    {
        $patient->delete();

        ActivityLog::log('deleted', "Patient {$patient->full_name} deleted", $patient);

        return response()->json(['message' => 'Patient deleted successfully.']);
    }
}
