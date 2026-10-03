@extends('layouts.app')

@section('title', 'My Dashboard')
@section('page-title', 'Welcome')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--text);">Welcome back, {{ explode(' ', auth()->user()->name)[0] }} 👋</h4>
        <p class="mb-0" style="color:var(--text-muted);font-size:.9rem;">Here's an overview of your appointments and account balance.</p>
    </div>
    <a href="{{ route('book-appointment') }}" class="btn btn-primary"><i class="fas fa-calendar-plus me-1"></i> Book Appointment</a>
</div>

<div class="row mb-1">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ number_format($outstandingBalance, 2) }}</h3>
                <p>Outstanding Amount</p>
            </div>
            <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $upcomingAppointments->count() }}</h3>
                <p>Upcoming Appointments</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-check"></i></div>
        </div>
    </div>
</div>

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
