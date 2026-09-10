@extends('layouts.app')

@section('title', 'My Dashboard')
@section('page-title', 'Welcome')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Upcoming Appointments</h3></div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Date</th><th>Dentist</th><th>Service</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($upcomingAppointments as $apt)
                            <tr>
                                <td>{{ $apt->appointment_date->format('Y-m-d') }} {{ $apt->start_time }}</td>
                                <td>{{ $apt->dentist->user->name ?? '-' }}</td>
                                <td>{{ $apt->service->name ?? '-' }}</td>
                                <td><span class="badge bg-{{ $apt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$apt->status)) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">No upcoming appointments.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Recent Invoices</h3></div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Invoice #</th><th>Total</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($invoices as $inv)
                            <tr>
                                <td>{{ $inv->invoice_no }}</td>
                                <td>{{ number_format($inv->total_amount, 2) }}</td>
                                <td><span class="badge bg-{{ $inv->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$inv->status)) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">No invoices yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
