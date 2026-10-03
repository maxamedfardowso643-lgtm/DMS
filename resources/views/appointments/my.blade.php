@extends('layouts.app')

@section('title', 'My Appointments')
@section('page-title', 'My Appointments')
@section('breadcrumb')
    <li class="breadcrumb-item active">My Appointments</li>
@endsection

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('book-appointment') }}" class="btn btn-primary btn-sm"><i class="fas fa-calendar-plus me-1"></i> Book Appointment</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead><tr><th>No.</th><th>Date</th><th>Time</th><th>Dentist</th><th>Service</th><th>Fee</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($appointments as $apt)
                    @php
                        $startsAt = \Carbon\Carbon::parse($apt->appointment_date->format('Y-m-d') . ' ' . $apt->start_time);
                        $canCancel = in_array($apt->status, ['booked', 'confirmed']) && $startsAt->isFuture();
                    @endphp
                    <tr>
                        <td>{{ $apt->appointment_no }}</td>
                        <td>{{ $apt->appointment_date->format('Y-m-d') }}</td>
                        <td>{{ substr($apt->start_time,0,5) }} - {{ substr($apt->end_time,0,5) }}</td>
                        <td>{{ $apt->dentist->user->name ?? '-' }}</td>
                        <td>{{ $apt->service->name ?? '-' }}</td>
                        <td>{{ $apt->service ? number_format($apt->service->price, 2) : '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $apt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$apt->status)) }}</span>
                            @if ($apt->status === 'cancelled' && $apt->cancellation_reason)
                                <div class="text-muted small">{{ $apt->cancellation_reason }}</div>
                            @endif
                        </td>
                        <td>
                            @if ($canCancel)
                                <button class="btn btn-outline-danger btn-xs" onclick="cancelAppointment({{ $apt->id }}, '{{ $apt->appointment_no }}')"><i class="fas fa-times me-1"></i> Cancel</button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-3">No appointments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('js')
<script>
function cancelAppointment(id, no) {
    Swal.fire({
        title: `Cancel ${no}?`,
        input: 'text',
        inputLabel: 'Reason (optional)',
        inputPlaceholder: 'e.g. I can no longer make it',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Yes, cancel it',
        cancelButtonText: 'Keep it',
    }).then(result => {
        if (!result.isConfirmed) return;

        $.post(`{{ url('my-appointments') }}/${id}/cancel`, { reason: result.value })
            .done(res => { toastr.success(res.message); setTimeout(() => location.reload(), 900); })
            .fail(xhr => toastr.error(xhr.responseJSON?.message || 'Something went wrong.'));
    });
}
</script>
@endpush
