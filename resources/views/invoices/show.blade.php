@extends('layouts.app')

@section('title', $invoice->invoice_no)
@section('page-title', 'Invoice Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Invoices</a></li>
    <li class="breadcrumb-item active">{{ $invoice->invoice_no }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">{{ $invoice->invoice_no }}
            <span class="badge bg-{{ $invoice->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$invoice->status)) }}</span>
        </h3>
        <a href="{{ route('invoices.pdf', $invoice) }}" target="_blank" class="btn btn-secondary btn-sm">
            <i class="fas fa-file-pdf"></i> Download PDF
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Patient:</strong> {{ $invoice->patient->full_name ?? '-' }} ({{ $invoice->patient->patient_code ?? '-' }})<br>
                <strong>Phone:</strong> {{ $invoice->patient->phone ?? '-' }}
            </div>
            <div class="col-md-6 text-md-end">
                <strong>Issue Date:</strong> {{ $invoice->issue_date->format('Y-m-d') }}<br>
                <strong>Due Date:</strong> {{ $invoice->due_date?->format('Y-m-d') ?? '-' }}
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr><th>Description</th><th>Tooth #</th><th>Qty</th><th>Unit Price</th><th>Discount</th><th>Tax</th><th>Total</th></tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->tooth_number ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ number_format($item->discount_amount, 2) }}</td>
                        <td>{{ number_format($item->tax_amount, 2) }}</td>
                        <td>{{ number_format($item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row justify-content-end">
            <div class="col-md-4">
                <table class="table table-sm">
                    <tr><th>Subtotal</th><td class="text-end">{{ number_format($invoice->subtotal, 2) }}</td></tr>
                    <tr><th>Discount</th><td class="text-end">{{ number_format($invoice->discount_amount, 2) }}</td></tr>
                    <tr><th>Tax</th><td class="text-end">{{ number_format($invoice->tax_amount, 2) }}</td></tr>
                    <tr><th>Grand Total</th><td class="text-end"><b>{{ number_format($invoice->total_amount, 2) }}</b></td></tr>
                    <tr><th>Paid</th><td class="text-end text-success">{{ number_format($invoice->paid_amount, 2) }}</td></tr>
                    <tr><th>Balance</th><td class="text-end text-danger"><b>{{ number_format($invoice->balance, 2) }}</b></td></tr>
                </table>
            </div>
        </div>

        @if ($invoice->notes)
            <p><strong>Notes:</strong> {{ $invoice->notes }}</p>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">Payment History</h3></div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead><tr><th>Payment #</th><th>Date</th><th>Amount</th><th>Method</th><th>Reference</th></tr></thead>
            <tbody>
                @forelse ($invoice->payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_no }}</td>
                        <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                        <td>{{ number_format($payment->amount, 2) }}</td>
                        <td class="text-capitalize">{{ str_replace('_',' ',$payment->method) }}</td>
                        <td>{{ $payment->reference_no ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No payments recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($invoice->balance > 0)
        <div class="card-footer">
            <a href="{{ route('payments.index') }}?invoice_id={{ $invoice->id }}" class="btn btn-success btn-sm">
                <i class="fas fa-money-bill-wave"></i> Record Payment
            </a>
        </div>
    @endif
</div>
@endsection
