@extends('layouts.app')

@section('title', 'My Invoices')
@section('page-title', 'My Invoices')
@section('breadcrumb')
    <li class="breadcrumb-item active">My Invoices</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead><tr><th>Invoice #</th><th>Date</th><th>Total</th><th>Paid</th><th>Balance</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($invoices as $inv)
                    <tr>
                        <td>{{ $inv->invoice_no }}</td>
                        <td>{{ $inv->issue_date->format('Y-m-d') }}</td>
                        <td>{{ number_format($inv->total_amount, 2) }}</td>
                        <td>{{ number_format($inv->paid_amount, 2) }}</td>
                        <td>{{ number_format($inv->balance, 2) }}</td>
                        <td><span class="badge bg-{{ $inv->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$inv->status)) }}</span></td>
                        <td><a href="{{ route('invoices.pdf', $inv) }}" target="_blank" class="btn btn-secondary btn-xs"><i class="fas fa-file-pdf"></i></a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">No invoices found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
