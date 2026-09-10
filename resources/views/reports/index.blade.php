@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports &amp; Analytics')
@section('breadcrumb')
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <div class="col-md-3"><label class="form-label">From</label><input type="date" name="from" class="form-control" value="{{ $from }}"></div>
            <div class="col-md-3"><label class="form-label">To</label><input type="date" name="to" class="form-control" value="{{ $to }}"></div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('reports.export') }}?from={{ $from }}&to={{ $to }}" class="btn btn-success"><i class="fas fa-file-excel"></i> Export</a>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3>{{ $totalAppointments }}</h3><p>Total Appointments</p></div>
            <div class="icon"><i class="fas fa-calendar-check"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-danger">
            <div class="inner"><h3>{{ $totalAppointments ? round($cancelled / $totalAppointments * 100, 1) : 0 }}%</h3><p>Cancellation Rate</p></div>
            <div class="icon"><i class="fas fa-times-circle"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner"><h3>{{ $totalAppointments ? round($noShow / $totalAppointments * 100, 1) : 0 }}%</h3><p>No-show Rate</p></div>
            <div class="icon"><i class="fas fa-user-slash"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Daily Cash Collection</h3></div>
            <div class="card-body"><canvas id="cashChart" height="150"></canvas></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Top Services</h3></div>
            <div class="card-body"><canvas id="servicesChart" height="150"></canvas></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Revenue by Dentist</h3></div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Dentist</th><th>Revenue</th></tr></thead>
                    <tbody>
                        @forelse ($revenueByDentist as $r)
                            <tr><td>{{ $r->dentist_name }}</td><td>{{ number_format($r->total, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-3">No data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Aging Receivables</h3></div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Bucket</th><th>Balance</th></tr></thead>
                    <tbody>
                        @forelse ($agingReceivables as $bucket => $amount)
                            <tr><td>{{ $bucket }}</td><td>{{ number_format($amount, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-3">No outstanding receivables.</td></tr>
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
    new Chart(document.getElementById('cashChart'), {
        type: 'line',
        data: {
            labels: @json($dailyCashCollection->pluck('payment_date')),
            datasets: [{ label: 'Cash Collected', data: @json($dailyCashCollection->pluck('total')), borderColor: '#28a745', tension: 0.3 }]
        },
        options: { responsive: true }
    });

    new Chart(document.getElementById('servicesChart'), {
        type: 'bar',
        data: {
            labels: @json($topServices->pluck('name')),
            datasets: [{ label: 'Appointments', data: @json($topServices->pluck('appointments_count')), backgroundColor: '#17a2b8' }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
});
</script>
@endpush
