<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\ActivityLog;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $query = Payment::with(['invoice', 'patient', 'paymentMethod'])->latest();

            if ($search = $request->get('search')['value'] ?? null) {
                $query->where('payment_no', 'like', "%$search%")
                    ->orWhereHas('patient', fn ($p) => $p->where('first_name', 'like', "%$search%")->orWhere('last_name', 'like', "%$search%"));
            }

            $total = Payment::count();
            $filtered = $query->count();
            $payments = $query->skip($request->get('start', 0))->take($request->get('length', 10))->get();

            return response()->json([
                'draw' => (int) $request->get('draw'),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered,
                'data' => $payments->map(fn ($p) => [
                    'id' => $p->id,
                    'payment_no' => $p->payment_no,
                    'invoice_no' => $p->invoice->invoice_no ?? '-',
                    'patient' => $p->patient->full_name,
                    'payment_date' => $p->payment_date->format('Y-m-d'),
                    'amount' => number_format($p->amount, 2),
                    'method' => $p->method,
                    'type' => $p->type,
                ]),
            ]);
        }

        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('sort_order')->get();
        $preselectInvoiceId = $request->get('invoice_id');
        $preselectPatientId = $preselectInvoiceId ? Invoice::find($preselectInvoiceId)?->patient_id : null;

        return view('payments.index', compact('paymentMethods', 'preselectInvoiceId', 'preselectPatientId'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('payments.index');
    }

    public function patientOutstanding(Patient $patient): JsonResponse
    {
        $invoices = $patient->invoices()
            ->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
            ->orderBy('issue_date')
            ->get(['id', 'invoice_no', 'issue_date', 'total_amount', 'paid_amount']);

        return response()->json([
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->full_name,
                'code' => $patient->patient_code,
                'phone' => $patient->phone,
            ],
            'invoices' => $invoices->map(fn ($i) => [
                'id' => $i->id,
                'invoice_no' => $i->invoice_no,
                'issue_date' => $i->issue_date->format('Y-m-d'),
                'total_amount' => (float) $i->total_amount,
                'balance' => (float) $i->balance,
            ]),
            'total_balance' => $invoices->sum(fn ($i) => (float) $i->balance),
        ]);
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        $invoice = Invoice::findOrFail($request->invoice_id);
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        $amountDue = round($invoice->balance - (float) ($request->discount_amount ?? 0), 2);
        if ($request->type === 'payment' && round((float) $request->amount, 2) > $amountDue) {
            return response()->json([
                'message' => 'Amount cannot be more than the amount due ($' . number_format(max($amountDue, 0), 2) . ').',
            ], 422);
        }

        $payment = DB::transaction(function () use ($request, $invoice, $paymentMethod) {
            if ($request->filled('discount_amount') && $request->discount_amount > 0) {
                $invoice->increment('discount_amount', $request->discount_amount);
                $invoice->decrement('total_amount', $request->discount_amount);
            }

            $payment = Payment::create([
                'payment_no' => $this->nextNumber('PAY'),
                'invoice_id' => $invoice->id,
                'patient_id' => $invoice->patient_id,
                'payment_date' => $request->payment_date,
                'amount' => $request->amount,
                'discount_amount' => $request->discount_amount ?? 0,
                'payment_method_id' => $paymentMethod->id,
                'method' => $paymentMethod->name,
                'sender_phone' => $request->sender_phone,
                'reference_no' => $request->reference_no,
                'type' => $request->type,
                'notes' => $request->notes,
                'received_by' => Auth::id(),
            ]);

            $this->recalculateInvoice($invoice->fresh());

            return $payment;
        });

        ActivityLog::log('created', "Payment {$payment->payment_no} recorded for invoice {$invoice->invoice_no}", $payment);

        return response()->json([
            'message' => 'Payment recorded successfully.',
            'payment' => $payment,
            'receipt_url' => route('payments.receipt', $payment),
        ], 201);
    }

    public function edit(Payment $payment): JsonResponse
    {
        $payment->load(['patient', 'invoice']);

        $invoices = $payment->patient->invoices()
            ->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
            ->orWhere('id', $payment->invoice_id)
            ->orderBy('issue_date')
            ->get(['id', 'invoice_no', 'issue_date', 'total_amount', 'paid_amount']);

        return response()->json([
            'payment' => $payment,
            'patient' => [
                'id' => $payment->patient->id,
                'name' => $payment->patient->full_name,
                'code' => $payment->patient->patient_code,
                'phone' => $payment->patient->phone,
            ],
            'invoices' => $invoices->map(fn ($i) => [
                'id' => $i->id,
                'invoice_no' => $i->invoice_no,
                'issue_date' => $i->issue_date->format('Y-m-d'),
                'total_amount' => (float) $i->total_amount,
                'balance' => $i->id === $payment->invoice_id
                    ? (float) $i->balance + (float) $payment->amount
                    : (float) $i->balance,
            ]),
        ]);
    }

    public function update(StorePaymentRequest $request, Payment $payment): JsonResponse
    {
        $oldInvoice = $payment->invoice;
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        $payment->update($request->validated() + [
            'method' => $paymentMethod->name,
        ]);

        $this->recalculateInvoice($oldInvoice->fresh());
        if ($payment->invoice_id !== $oldInvoice->id) {
            $this->recalculateInvoice(Invoice::find($payment->invoice_id));
        }

        ActivityLog::log('updated', "Payment {$payment->payment_no} updated", $payment);

        return response()->json(['message' => 'Payment updated successfully.']);
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $invoice = $payment->invoice;
        $payment->delete();
        $this->recalculateInvoice($invoice);

        ActivityLog::log('deleted', "Payment {$payment->payment_no} deleted", $payment);

        return response()->json(['message' => 'Payment deleted successfully.']);
    }

    public function receipt(Payment $payment): View
    {
        $payment->load(['patient', 'invoice', 'paymentMethod', 'receivedBy']);

        return view('payments.receipt', compact('payment'));
    }

    protected function nextNumber(string $prefix): string
    {
        $year = date('Y');
        $last = Payment::where('payment_no', 'like', "$prefix-$year-%")
            ->withTrashed()->orderByDesc('payment_no')->value('payment_no');
        $next = $last ? ((int) substr($last, -5)) + 1 : 1;

        return "$prefix-$year-" . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    protected function recalculateInvoice(Invoice $invoice): void
    {
        $paid = $invoice->payments()->where('type', 'payment')->sum('amount')
            - $invoice->payments()->whereIn('type', ['refund', 'credit_note'])->sum('amount');

        $status = match (true) {
            $paid <= 0 => 'unpaid',
            $paid >= (float) $invoice->total_amount => 'paid',
            default => 'partially_paid',
        };

        if ($status !== 'paid' && $invoice->due_date && $invoice->due_date->isPast()) {
            $status = 'overdue';
        }

        $invoice->update(['paid_amount' => max(0, $paid), 'status' => $status]);
    }
}
