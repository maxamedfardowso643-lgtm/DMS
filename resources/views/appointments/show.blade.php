@extends('layouts.app')

@section('title', $appointment->appointment_no)
@section('page-title', 'Appointment Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('appointments.index') }}">Appointments</a></li>
    <li class="breadcrumb-item active">{{ $appointment->appointment_no }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Appointment Info</h3>
                @if (!in_array($appointment->status, ['completed', 'cancelled', 'no_show']))
                    <button class="btn btn-success btn-sm" id="complete-btn" onclick="completeAppointment({{ $appointment->id }})">
                        <i class="fas fa-check-circle"></i> Complete Appointment
                    </button>
                @endif
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Appointment #</th><td>{{ $appointment->appointment_no }}</td></tr>
                    <tr><th>Patient</th><td>{{ $appointment->patient->full_name ?? '-' }}</td></tr>
                    <tr><th>Dentist</th><td>{{ $appointment->dentist->user->name ?? '-' }}</td></tr>
                    <tr><th>Service</th><td>{{ $appointment->service->name ?? '-' }}</td></tr>
                    <tr><th>Date</th><td>{{ $appointment->appointment_date->format('Y-m-d') }}</td></tr>
                    <tr><th>Time</th><td>{{ substr($appointment->start_time,0,5) }} - {{ substr($appointment->end_time,0,5) }}</td></tr>
                    <tr><th>Source</th><td class="text-capitalize">{{ str_replace('_',' ',$appointment->source) }}</td></tr>
                    <tr><th>Status</th><td><span class="badge bg-{{ $appointment->statusBadgeColor() }}" id="status-badge">{{ ucfirst(str_replace('_',' ',$appointment->status)) }}</span></td></tr>
                    <tr><th>Notes</th><td>{{ $appointment->notes ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Status Timeline</h3></div>
            <div class="card-body">
                <div class="timeline">
                    @foreach ($appointment->statusLogs as $log)
                        <div class="time-label">
                            <span class="bg-secondary">{{ $log->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-circle bg-primary"></i>
                            <div class="timeline-item">
                                <div class="timeline-body">
                                    <strong>{{ ucfirst(str_replace('_',' ',$log->to_status)) }}</strong>
                                    @if ($log->from_status)
                                        <span class="text-muted">(from {{ ucfirst(str_replace('_',' ',$log->from_status)) }})</span>
                                    @endif
                                    <br>
                                    <small class="text-muted">by {{ $log->changedBy->name ?? 'System' }}</small>
                                    @if ($log->remarks)
                                        <p class="mb-0">{{ $log->remarks }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div><i class="far fa-clock bg-gray"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
function completeAppointment(id) {
    Swal.fire({
        title: 'Complete Appointment?',
        text: 'This will mark the appointment as "Completed" and cannot be undone.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, complete it',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
    }).then((result) => {
        if (!result.isConfirmed) return;

        $.post(`/appointments/${id}/status`, { status: 'completed', remarks: 'Appointment completed' })
            .done(function () {
                toastr.success('Appointment marked as completed.');
                setTimeout(() => window.location.reload(), 800);
            })
            .fail(function (xhr) {
                toastr.error(xhr.responseJSON?.message || 'Could not update status.');
            });
    });
}
</script>
@endpush
