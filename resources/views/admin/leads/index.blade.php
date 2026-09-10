@extends('layouts.app')

@section('title', 'Website Leads')
@section('page-title', 'Website Leads')
@section('breadcrumb')
    <li class="breadcrumb-item active">Website Leads</li>
@endsection

@section('content')
<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#booking-tab">Appointment Requests <span class="badge bg-primary ms-1">{{ $bookingRequests->count() }}</span></a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#messages-tab">Contact Messages <span class="badge bg-secondary ms-1">{{ $contactMessages->count() }}</span></a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="booking-tab">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Name</th><th>Phone</th><th>Service</th><th>Preferred</th><th>Notes</th><th>Status</th><th>Received</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse ($bookingRequests as $r)
                            <tr>
                                <td>{{ $r->name }}</td>
                                <td>{{ $r->phone }}</td>
                                <td>{{ $r->service->name ?? '-' }}</td>
                                <td>{{ $r->preferred_date?->format('Y-m-d') ?? '-' }} {{ $r->preferred_time }}</td>
                                <td style="max-width:200px;">{{ \Illuminate\Support\Str::limit($r->notes, 60) ?: '-' }}</td>
                                <td>
                                    <select class="form-select form-select-sm status-select" data-id="{{ $r->id }}" style="width:auto;display:inline-block;">
                                        @foreach (['pending','contacted','converted','dismissed'] as $status)
                                            <option value="{{ $status }}" {{ $r->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>{{ $r->created_at->diffForHumans() }}</td>
                                <td><button class="btn btn-danger btn-xs" onclick="deleteRequest({{ $r->id }})"><i class="fas fa-trash"></i></button></td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">No appointment requests yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane" id="messages-tab">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Name</th><th>Contact</th><th>Subject</th><th>Message</th><th>Received</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse ($contactMessages as $m)
                            <tr class="{{ $m->is_read ? '' : 'fw-bold' }}">
                                <td>{{ $m->name }}</td>
                                <td>{{ $m->phone ?? $m->email ?? '-' }}</td>
                                <td>{{ $m->subject ?? '-' }}</td>
                                <td style="max-width:280px;">{{ \Illuminate\Support\Str::limit($m->message, 80) }}</td>
                                <td>{{ $m->created_at->diffForHumans() }}</td>
                                <td>
                                    @if (!$m->is_read)
                                        <button class="btn btn-secondary btn-xs" onclick="markRead({{ $m->id }})"><i class="fas fa-envelope-open"></i></button>
                                    @endif
                                    <button class="btn btn-danger btn-xs" onclick="deleteMessage({{ $m->id }})"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No contact messages yet.</td></tr>
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
$('.status-select').on('change', function () {
    const id = $(this).data('id');
    $.post(`/leads/requests/${id}/status`, { status: $(this).val() })
        .done(res => toastr.success(res.message))
        .fail(() => toastr.error('Could not update status.'));
});

function deleteRequest(id) {
    confirmDelete(`/leads/requests/${id}`, () => location.reload());
}

function markRead(id) {
    $.post(`/leads/messages/${id}/read`).done(() => location.reload());
}

function deleteMessage(id) {
    confirmDelete(`/leads/messages/${id}`, () => location.reload());
}
</script>
@endpush
