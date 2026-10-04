@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--text);">Welcome back, {{ explode(' ', auth()->user()->name)[0] }} 👋</h4>
        <p class="mb-0" style="color:var(--text-muted);font-size:.9rem;">Here's what's happening at your clinic today, {{ now()->format('l, F j, Y') }}.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('appointments.index', ['book' => 1]) }}" class="btn btn-primary btn-sm"><i class="fas fa-calendar-plus me-1"></i> New Appointment</a>
        <a href="{{ route('invoices.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-file-invoice me-1"></i> New Invoice</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info stat-link" role="button" tabindex="0" data-bs-toggle="modal" data-bs-target="#modalTodayAppointments" title="Click to view details">
            <div class="inner">
                <h3>{{ $stats['todays_appointments'] }}</h3>
                <p>Today's Appointments</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-check"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success stat-link" role="button" tabindex="0" data-bs-toggle="modal" data-bs-target="#modalPatients" title="Click to view details">
            <div class="inner">
                <h3>{{ $stats['total_patients'] }}</h3>
                <p>Total Patients</p>
            </div>
            <div class="icon"><i class="fas fa-user-injured"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning stat-link" role="button" tabindex="0" data-bs-toggle="modal" data-bs-target="#modalPendingInvoices" title="Click to view details">
            <div class="inner">
                <h3>{{ $stats['pending_invoices'] }}</h3>
                <p>Pending Invoices</p>
            </div>
            <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger stat-link" role="button" tabindex="0" data-bs-toggle="modal" data-bs-target="#modalRevenue" title="Click to view details">
            <div class="inner">
                <h3>${{ number_format($stats['revenue_this_month'], 0) }}</h3>
                <p>Revenue This Month</p>
            </div>
            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Appointments (Last 7 Days)</h3>
            </div>
            <div class="card-body"><canvas id="appointmentsChart" height="110"></canvas></div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Net Balance of System</h3></div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:120px;flex-shrink:0;"><canvas id="balanceChart"></canvas></div>
                    <div class="flex-grow-1">
                        <div style="color:var(--text-muted);font-size:.8rem;">Net Balance (collected − refunds)</div>
                        <div class="fw-bold mb-2" style="font-size:1.6rem;color:var(--text);">${{ number_format($netBalance['net'], 2) }}</div>
                        <table class="table table-sm table-borderless mb-0" style="font-size:.85rem;">
                            <tr><td class="ps-0"><span class="badge rounded-pill me-1" style="background:#4f46e5;">&nbsp;</span>Total Billed</td><td class="text-end pe-0">${{ number_format($netBalance['billed'], 2) }}</td></tr>
                            <tr><td class="ps-0"><span class="badge rounded-pill me-1" style="background:#10b981;">&nbsp;</span>Collected</td><td class="text-end pe-0">${{ number_format($netBalance['collected'], 2) }}</td></tr>
                            <tr><td class="ps-0"><span class="badge rounded-pill me-1" style="background:#ef4444;">&nbsp;</span>Refunds</td><td class="text-end pe-0">${{ number_format($netBalance['refunds'], 2) }}</td></tr>
                            <tr><td class="ps-0"><span class="badge rounded-pill me-1" style="background:#f59e0b;">&nbsp;</span>Outstanding</td><td class="text-end pe-0">${{ number_format($netBalance['outstanding'], 2) }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Today's Schedule</h3></div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Time</th><th>Patient</th><th>Dentist</th><th>Service</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($todaysAppointments as $apt)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($apt->start_time)->format('H:i') }}</td>
                                <td>{{ $apt->patient->full_name ?? '-' }}</td>
                                <td>{{ $apt->dentist->user->name ?? '-' }}</td>
                                <td>{{ $apt->service->name ?? '-' }}</td>
                                <td><span class="badge bg-{{ $apt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$apt->status)) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4" style="color:var(--text-soft);">No appointments today.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Low Stock Alerts</h3></div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Item</th><th>Qty</th><th>Reorder</th></tr></thead>
                    <tbody>
                        @forelse ($lowStockItems as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td><span class="badge bg-danger">{{ $item->quantity_on_hand }}</span></td>
                                <td>{{ $item->reorder_level }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-4" style="color:var(--text-soft);">All stock levels healthy.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@php($user = auth()->user())

{{-- Today's Appointments --}}
<div class="modal fade" id="modalTodayAppointments" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-calendar-check me-2 text-info"></i>Today's Appointments ({{ $todaysAppointments->count() }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Time</th><th>Patient</th><th>Dentist</th><th>Service</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($todaysAppointments as $apt)
                            <tr>
                                <td>{{ \Illuminate\Support\Carbon::parse($apt->start_time)->format('H:i') }}</td>
                                <td>{{ $apt->patient->full_name ?? '-' }}</td>
                                <td>{{ $apt->dentist->user->name ?? '-' }}</td>
                                <td>{{ $apt->service->name ?? '-' }}</td>
                                <td><span class="badge bg-{{ $apt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$apt->status)) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4" style="color:var(--text-soft);">No appointments today.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($user->hasAnyRole(['admin', 'receptionist', 'dentist']))
                <div class="modal-footer"><a href="{{ route('appointments.index') }}" class="btn btn-primary btn-sm">View all appointments</a></div>
            @endif
        </div>
    </div>
</div>

{{-- Patients --}}
<div class="modal fade" id="modalPatients" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-injured me-2 text-success"></i>Patients ({{ $stats['total_patients'] }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Code</th><th>Name</th><th>Gender</th><th>Phone</th><th>Registered</th></tr></thead>
                    <tbody>
                        @forelse ($recentPatients as $p)
                            <tr>
                                <td>{{ $p->patient_code }}</td>
                                <td>{{ $p->full_name }}</td>
                                <td>{{ ucfirst($p->gender ?? '-') }}</td>
                                <td>{{ $p->phone ?? '-' }}</td>
                                <td>{{ $p->created_at?->format('M j, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4" style="color:var(--text-soft);">No patients yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <small style="color:var(--text-muted);">
                    @if ($stats['total_patients'] > $recentPatients->count()) Showing the {{ $recentPatients->count() }} most recent. @endif
                </small>
                @if ($user->hasAnyRole(['admin', 'receptionist']))
                    <a href="{{ route('patients.index') }}" class="btn btn-primary btn-sm">View all patients</a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Pending Invoices --}}
<div class="modal fade" id="modalPendingInvoices" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-invoice-dollar me-2 text-warning"></i>Pending Invoices ({{ $pendingInvoices->count() }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Invoice</th><th>Patient</th><th>Due Date</th><th class="text-end">Total</th><th class="text-end">Balance</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($pendingInvoices as $inv)
                            <tr>
                                <td>{{ $inv->invoice_no }}</td>
                                <td>{{ $inv->patient->full_name ?? '-' }}</td>
                                <td>{{ $inv->due_date?->format('M j, Y') ?? '-' }}</td>
                                <td class="text-end">${{ number_format($inv->total_amount, 2) }}</td>
                                <td class="text-end fw-semibold">${{ number_format($inv->balance, 2) }}</td>
                                <td><span class="badge bg-{{ $inv->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$inv->status)) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4" style="color:var(--text-soft);">No pending invoices.</td></tr>
                        @endforelse
                    </tbody>
                    @if ($pendingInvoices->isNotEmpty())
                        <tfoot><tr class="fw-bold"><td colspan="4">Total outstanding</td><td class="text-end">${{ number_format($pendingInvoices->sum(fn ($i) => (float) $i->balance), 2) }}</td><td></td></tr></tfoot>
                    @endif
                </table>
            </div>
            @if ($user->hasAnyRole(['admin', 'receptionist', 'accountant']))
                <div class="modal-footer"><a href="{{ route('invoices.index') }}" class="btn btn-primary btn-sm">View all invoices</a></div>
            @endif
        </div>
    </div>
</div>

{{-- Revenue This Month --}}
<div class="modal fade" id="modalRevenue" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-dollar-sign me-2 text-danger"></i>Revenue — {{ now()->format('F Y') }} ({{ $monthPayments->count() }} payments)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Date</th><th>Payment No</th><th>Patient</th><th>Method</th><th class="text-end">Amount</th></tr></thead>
                    <tbody>
                        @forelse ($monthPayments as $pay)
                            <tr>
                                <td>{{ $pay->payment_date?->format('M j, Y') }}</td>
                                <td>{{ $pay->payment_no }}</td>
                                <td>{{ $pay->patient->full_name ?? '-' }}</td>
                                <td>{{ $pay->paymentMethod->name ?? ucfirst(str_replace('_', ' ', $pay->method ?? '-')) }}</td>
                                <td class="text-end">${{ number_format($pay->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4" style="color:var(--text-soft);">No payments received this month.</td></tr>
                        @endforelse
                    </tbody>
                    @if ($monthPayments->isNotEmpty())
                        <tfoot><tr class="fw-bold"><td colspan="4">Total</td><td class="text-end">${{ number_format($stats['revenue_this_month'], 2) }}</td></tr></tfoot>
                    @endif
                </table>
            </div>
            @if ($user->hasAnyRole(['admin', 'receptionist', 'accountant']))
                <div class="modal-footer"><a href="{{ route('payments.index') }}" class="btn btn-primary btn-sm">View all payments</a></div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .stat-link { cursor: pointer; transition: transform .15s ease, box-shadow .15s ease; }
    .stat-link:hover, .stat-link:focus-visible { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(0,0,0,.15); outline: none; }
</style>
@endpush

@push('js')
<script>
$(function () {
    $('.stat-link').on('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); this.click(); }
    });

    const gridColor = getComputedStyle(document.documentElement).getPropertyValue('--border').trim();
    const textColor = getComputedStyle(document.documentElement).getPropertyValue('--text-muted').trim();
    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = gridColor;

    const apptLabels = @json($appointmentsPerDay->map(fn ($d) => \Illuminate\Support\Carbon::parse($d->appointment_date)->format('D, M j')));
    const apptData = @json($appointmentsPerDay->pluck('total'));
    new Chart(document.getElementById('appointmentsChart'), {
        type: 'bar',
        data: { labels: apptLabels, datasets: [{ label: 'Appointments', data: apptData, backgroundColor: '#4f46e5', borderRadius: 6, maxBarThickness: 34 }] },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
    });

    new Chart(document.getElementById('balanceChart'), {
        type: 'doughnut',
        data: {
            labels: ['Net Balance', 'Refunds', 'Outstanding'],
            datasets: [{
                data: [{{ max($netBalance['net'], 0) }}, {{ $netBalance['refunds'] }}, {{ $netBalance['outstanding'] }}],
                backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true, cutout: '70%',
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => `${c.label}: $${c.parsed.toLocaleString(undefined, { minimumFractionDigits: 2 })}` } } }
        }
    });
});
</script>
@endpush
