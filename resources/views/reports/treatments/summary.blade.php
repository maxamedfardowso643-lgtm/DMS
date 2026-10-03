@extends('layouts.app')

@section('title', 'Treatment Summary Report')
@section('page-title', 'Treatment Summary Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports &amp; Analytics</a></li>
    <li class="breadcrumb-item active">Treatment Summary</li>
@endsection

@section('content')
@include('reports.partials.filter-bar', [
    'exportRoute' => 'reports.treatments.summary.export',
    'exportPdfRoute' => 'reports.treatments.summary.pdf',
])

<div class="card report-table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Treatment Summary</h3>
        <span class="text-muted small">{{ $rows->count() }} treatment type(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle report-table">
                <thead class="sticky-top">
                    <tr>
                        <th>Treatment</th><th>Category</th><th>Total</th><th>Patients</th>
                        <th>Completed</th><th>Pending</th><th>Cancelled</th><th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $r)
                        <tr>
                            <td>{{ $r->name }}</td>
                            <td>{{ $r->category ? ucfirst($r->category) : '-' }}</td>
                            <td>
                                <a href="{{ route('reports.appointments.summary', array_filter(['service_id' => $r->id, 'preset' => $range['preset'], 'from' => $range['from'], 'to' => $range['to']])) }}">{{ $r->total }}</a>
                            </td>
                            <td>{{ $r->patients }}</td>
                            <td>{{ $r->completed }}</td>
                            <td>{{ $r->pending }}</td>
                            <td>{{ $r->cancelled }}</td>
                            <td>{{ number_format($r->revenue, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-5"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>No data found for the selected filters.</td></tr>
                    @endforelse
                </tbody>
                @if($rows->isNotEmpty())
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="2">Total</td>
                            <td>{{ $rows->sum('total') }}</td>
                            <td>{{ $rows->sum('patients') }}</td>
                            <td>{{ $rows->sum('completed') }}</td>
                            <td>{{ $rows->sum('pending') }}</td>
                            <td>{{ $rows->sum('cancelled') }}</td>
                            <td>{{ number_format($rows->sum('revenue'), 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
