<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $query = Invoice::with('patient')->latest();

            if ($search = $request->get('search')['value'] ?? null) {
                $query->where('invoice_no', 'like', "%$search%")
                    ->orWhereHas('patient', fn ($p) => $p->where('first_name', 'like', "%$search%")->orWhere('last_name', 'like', "%$search%"));
            }

            $total = Invoice::count();
            $filtered = $query->count();
            $invoices = $query->skip($request->get('start', 0))->take($request->get('length', 10))->get();

            return response()->json([
                'draw' => (int) $request->get('draw'),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered,
                'data' => $invoices->map(fn ($i) => [
                    'id' => $i->id,
                    'invoice_no' => $i->invoice_no,
                    'patient' => $i->patient->full_name,
                    'issue_date' => $i->issue_date->format('Y-m-d'),
                    'total_amount' => number_format($i->total_amount, 2),
                    'paid_amount' => number_format($i->paid_amount, 2),
                    'balance' => number_format($i->balance, 2),
                    'status' => $i->status,
                    'status_color' => $i->statusBadgeColor(),
                    'editable' => $i->isEditable(),
                ]),
            ]);
        }

        $patients = Patient::orderBy('first_name')->get();
        $services = Service::where('is_active', true)->get();

        return view('invoices.index', compact('patients', 'services'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('invoices.index');
    }

    public function appointmentServices(Appointment $appointment): JsonResponse
    {
        return response()->json([
            'service' => $appointment->service,
        ]);
    }

    public function store(StoreInvoiceRequest $request): JsonResponse
    {
        $invoice = DB::transaction(function () use ($request) {
            [$subtotal, $discountTotal, $taxTotal, $grandTotal] = $this->calculateTotals($request->items);

            $invoice = Invoice::create([
                'invoice_no' => Invoice::nextNumber(),
                'appointment_id' => $request->appointment_id,
                'patient_id' => $request->patient_id,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'subtotal' => $subtotal,
                'discount_amount' => $discountTotal,
                'tax_amount' => $taxTotal,
                'total_amount' => $grandTotal,
                'paid_amount' => 0,
                'status' => 'unpaid',
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            $this->saveItems($invoice, $request->items);

            return $invoice;
        });

        ActivityLog::log('created', "Invoice {$invoice->invoice_no} created", $invoice);

        return response()->json(['message' => 'Invoice created successfully.', 'invoice' => $invoice], 201);
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['patient', 'items.service', 'payments', 'appointment']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): JsonResponse
    {
        $invoice->load(['items', 'patient']);

        return response()->json($invoice);
    }

    public function update(StoreInvoiceRequest $request, Invoice $invoice): JsonResponse
    {
        if (! $invoice->isEditable()) {
            return response()->json(['message' => 'Only draft or unpaid invoices can be edited.'], 422);
        }

        DB::transaction(function () use ($request, $invoice) {
            [$subtotal, $discountTotal, $taxTotal, $grandTotal] = $this->calculateTotals($request->items);

            $invoice->update([
                'patient_id' => $request->patient_id,
                'appointment_id' => $request->appointment_id,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'subtotal' => $subtotal,
                'discount_amount' => $discountTotal,
                'tax_amount' => $taxTotal,
                'total_amount' => $grandTotal,
                'notes' => $request->notes,
            ]);

            $invoice->items()->delete();
            $this->saveItems($invoice, $request->items);
        });

        ActivityLog::log('updated', "Invoice {$invoice->invoice_no} updated", $invoice);

        return response()->json(['message' => 'Invoice updated successfully.']);
    }

    public function destroy(Invoice $invoice): JsonResponse
    {
        if ($invoice->paid_amount > 0) {
            return response()->json(['message' => 'Cannot delete an invoice with recorded payments.'], 422);
        }

        $invoice->delete();

        ActivityLog::log('deleted', "Invoice {$invoice->invoice_no} deleted", $invoice);

        return response()->json(['message' => 'Invoice deleted successfully.']);
    }

    public function pdf(Invoice $invoice)
    {
        $invoice->load(['patient', 'items.service', 'payments']);

        if (! class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return response('PDF export requires the barryvdh/laravel-dompdf package to be installed.', 503);
        }

        $pdf = app('dompdf.wrapper')->loadView('invoices.pdf', compact('invoice'));

        return $pdf->stream("{$invoice->invoice_no}.pdf");
    }

    public function myInvoices(): View
    {
        $patient = Auth::user()->patient;
        $invoices = $patient ? $patient->invoices()->latest()->get() : collect();

        return view('invoices.my', compact('invoices'));
    }

    protected function calculateTotals(array $items): array
    {
        $subtotal = 0;
        $discountTotal = 0;
        $taxTotal = 0;

        foreach ($items as $item) {
            $lineBase = $item['quantity'] * $item['unit_price'];
            $subtotal += $lineBase;
            $discountTotal += $item['discount_amount'] ?? 0;
            $taxTotal += $item['tax_amount'] ?? 0;
        }

        $grandTotal = $subtotal - $discountTotal + $taxTotal;

        return [$subtotal, $discountTotal, $taxTotal, $grandTotal];
    }

    protected function saveItems(Invoice $invoice, array $items): void
    {
        foreach ($items as $item) {
            $lineTotal = ($item['quantity'] * $item['unit_price']) - ($item['discount_amount'] ?? 0) + ($item['tax_amount'] ?? 0);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'service_id' => $item['service_id'] ?? null,
                'description' => $item['description'],
                'tooth_number' => $item['tooth_number'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount_amount' => $item['discount_amount'] ?? 0,
                'tax_amount' => $item['tax_amount'] ?? 0,
                'line_total' => $lineTotal,
            ]);
        }
    }
}
