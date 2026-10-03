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
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['todays_appointments'] }}</h3>
                <p>Today's Appointments</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-check"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['total_patients'] }}</h3>
                <p>Total Patients</p>
            </div>
            <div class="icon"><i class="fas fa-user-injured"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['pending_invoices'] }}</h3>
                <p>Pending Invoices</p>
            </div>
            <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
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
                                <td>{{ $apt->patient->full_name }}</td>
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
@endsection

@push('js')
<script>
$(function () {
    const gridColor = getComputedStyle(document.documentElement).getPropertyValue('--border').trim();
    const textColor = getComputedStyle(document.documentElement).getPropertyValue('--text-muted').trim();
    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = gridColor;

    const apptLabels = @json($appointmentsPerDay->pluck('appointment_date'));
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
