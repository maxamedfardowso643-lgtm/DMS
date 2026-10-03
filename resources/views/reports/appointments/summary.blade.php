@extends('layouts.app')

@section('title', 'Appointment Summary Report')
@section('page-title', 'Appointment Summary Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports &amp; Analytics</a></li>
    <li class="breadcrumb-item active">Appointment Summary</li>
@endsection

@section('content')
@include('reports.partials.filter-bar', [
    'exportRoute' => 'reports.appointments.summary.export',
    'exportPdfRoute' => 'reports.appointments.summary.pdf',
    'showSource' => true,
])

<div class="card report-table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Appointments</h3>
        <span class="text-muted small">{{ $appointments->total() }} result(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle report-table">
                <thead class="sticky-top">
                    <tr>
                        <th>Appointment No</th><th>Patient</th><th>Dentist</th><th>Service</th>
                        <th>Date</th><th>Time</th><th>Status</th><th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($appointments as $a)
                        <tr>
                            <td><a href="{{ route('appointments.show', $a) }}">{{ $a->appointment_no }}</a></td>
                            <td>{{ $a->patient->full_name ?? '-' }}</td>
                            <td>{{ $a->dentist->user->name ?? '-' }}</td>
                            <td>{{ $a->service->name ?? '-' }}</td>
                            <td>{{ $a->appointment_date->format('M j, Y') }}</td>
                            <td>{{ substr($a->start_time, 0, 5) }}</td>
                            <td><span class="badge text-bg-{{ match($a->status){'completed'=>'success','cancelled'=>'danger','no_show'=>'warning',default=>'info'} }}">{{ ucfirst(str_replace('_',' ',$a->status)) }}</span></td>
                            <td>{{ $a->source === 'walk_in' ? 'Walk-in' : 'Scheduled' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-5"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>No data found for the selected filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($appointments->hasPages())
        <div class="card-footer">{{ $appointments->links() }}</div>
    @endif
</div>
@endsection
