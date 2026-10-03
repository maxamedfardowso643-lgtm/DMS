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
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#comments-tab">Website Comments <span class="badge bg-warning ms-1">{{ $testimonials->where('is_read', false)->count() }}</span></a></li>
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
                                <td>
                                    @if ($r->status !== 'converted')
                                        <button type="button" class="btn btn-success btn-xs convert-lead-btn" title="Convert to appointment"
                                            data-lead="{{ json_encode(['id' => $r->id, 'name' => $r->name, 'phone' => $r->phone, 'email' => $r->email, 'service_id' => $r->service_id, 'preferred_date' => $r->preferred_date?->format('Y-m-d'), 'notes' => $r->notes]) }}">
                                            <i class="fas fa-calendar-check"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-danger btn-xs" onclick="deleteRequest({{ $r->id }})"><i class="fas fa-trash"></i></button>
                                </td>
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
                                <td>
                                    @if ($m->phone)<div><i class="fas fa-phone text-muted me-1"></i>{{ $m->phone }}</div>@endif
                                    @if ($m->email)<div><i class="fas fa-envelope text-muted me-1"></i>{{ $m->email }}</div>@endif
                                    @if (!$m->phone && !$m->email)-@endif
                                </td>
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

    <div class="tab-pane" id="comments-tab">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Name</th><th>Contact</th><th>Rating</th><th>Comment</th><th>Shown on site?</th><th>Received</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse ($testimonials as $t)
                            <tr class="{{ $t->is_read ? '' : 'fw-bold' }}">
                                <td>{{ $t->name }}</td>
                                <td>{{ $t->phone ?? $t->email ?? '-' }}</td>
                                <td>{{ str_repeat('★', $t->rating) }}</td>
                                <td style="max-width:280px;">{{ \Illuminate\Support\Str::limit($t->message, 80) }}</td>
                                <td>
                                    @if ($t->is_approved)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $t->created_at->diffForHumans() }}</td>
                                <td>
                                    @if ($t->is_approved)
                                        <button class="btn btn-secondary btn-xs" title="Hide from website" onclick="unapproveTestimonial({{ $t->id }})"><i class="fas fa-eye-slash"></i></button>
                                    @else
                                        <button class="btn btn-success btn-xs" title="Approve &amp; show on website" onclick="approveTestimonial({{ $t->id }})"><i class="fas fa-check"></i></button>
                                    @endif
                                    <button class="btn btn-primary btn-xs" title="Add to Appointment Requests leads" onclick="addTestimonialAsLead({{ $t->id }})"><i class="fas fa-user-plus"></i></button>
                                    <button class="btn btn-danger btn-xs" onclick="deleteTestimonial({{ $t->id }})"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No website comments yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Convert Lead to Appointment Modal -->
<div class="modal fade" id="convertModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="convert-form">
                <div class="modal-header">
                    <h5 class="modal-title">Convert Lead to Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="patient_id" id="convert_patient_id">
                    <div class="mb-3">
                        <label class="form-label">Existing Patient (optional)</label>
                        <div class="patient-search-box">
                            <input type="text" id="convert_patient_search" class="form-control" placeholder="Search by name, ID, mobile or emergency contact..." autocomplete="off">
                            <div class="search-results" id="convert_patient_results"></div>
                        </div>
                        <small class="text-muted" id="convert_patient_meta">Leave blank to register a new patient using the details below.</small>
                    </div>
                    <div class="row" id="convert_new_patient_fields">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" id="convert_first_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" id="convert_last_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone *</label>
                            <input type="text" name="phone" id="convert_phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="convert_email" class="form-control">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dentist *</label>
                            <select name="dentist_id" id="convert_dentist_select" class="form-control" required>
                                <option value="">Select dentist</option>
                                @foreach ($dentists as $d)
                                    <option value="{{ $d->id }}">{{ $d->user->name }} ({{ $d->specialization }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Service *</label>
                            <select name="service_id" id="convert_service_select" class="form-control" required>
                                <option value="">Select service</option>
                                @foreach ($services as $s)
                                    <option value="{{ $s->id }}" data-duration="{{ $s->duration_minutes }}">{{ $s->name }} ({{ $s->duration_minutes }} min)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date *</label>
                            <input type="date" name="appointment_date" id="convert_appointment_date" class="form-control" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Available Time Slots *</label>
                            <select name="start_time" id="convert_slots_select" class="form-control" required>
                                <option value="">Select dentist, service &amp; date first</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" id="convert_notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$('.status-select').on('change', function () {
    const id = $(this).data('id');
    const $select = $(this);

    if ($select.val() === 'converted') {
        const row = $select.closest('tr');
        $select.val($select.data('previous') || 'pending');
        const convertBtn = row.find('.btn-success');
        if (convertBtn.length) {
            convertBtn.trigger('click');
        }
        return;
    }

    $select.data('previous', $select.val());

    $.post(`/leads/requests/${id}/status`, { status: $select.val() })
        .done(res => toastr.success(res.message))
        .fail(() => toastr.error('Could not update status.'));
}).each(function () {
    $(this).data('previous', $(this).val());
});

$(document).on('click', '.convert-lead-btn', function () {
    openConvertModal($(this).data('lead'));
});

let convertRequestId = null;

initPatientSearch('#convert_patient_search', '#convert_patient_results', function (patient) {
    $('#convert_patient_id').val(patient.id);
    $('#convert_patient_meta').text(patient.meta);
    $('#convert_new_patient_fields').hide();
});

$('#convert_patient_search').on('input', function () {
    if ($(this).val().trim() === '') {
        $('#convert_patient_id').val('');
        $('#convert_patient_meta').text('Leave blank to register a new patient using the details below.');
        $('#convert_new_patient_fields').show();
    }
});

function openConvertModal(lead) {
    convertRequestId = lead.id;
    $('#convert-form')[0].reset();
    $('#convert_patient_id').val('');
    $('#convert_patient_search').val('');
    $('#convert_patient_meta').text('Leave blank to register a new patient using the details below.');
    $('#convert_new_patient_fields').show();
    $('#convert_slots_select').html('<option value="">Select dentist, service &amp; date first</option>');

    const parts = (lead.name || '').trim().split(/\s+/);
    $('#convert_first_name').val(parts.shift() || '');
    $('#convert_last_name').val(parts.join(' '));
    $('#convert_phone').val(lead.phone || '');
    $('#convert_email').val(lead.email || '');
    $('#convert_service_select').val(lead.service_id || '');
    $('#convert_appointment_date').val(lead.preferred_date || '');
    $('#convert_notes').val(lead.notes || '');

    $('#convertModal').modal('show');
    fetchConvertSlots();
}

function fetchConvertSlots() {
    const dentist_id = $('#convert_dentist_select').val();
    const service_id = $('#convert_service_select').val();
    const date = $('#convert_appointment_date').val();
    if (!dentist_id || !service_id || !date) return;

    $.get('{{ route('appointments.available-slots') }}', { dentist_id, service_id, date }, function (res) {
        const select = $('#convert_slots_select');
        select.empty();
        if (res.slots.length === 0) {
            select.append(`<option value="">${res.message || 'No slots available'}</option>`);
        } else {
            select.append('<option value="">Select a time</option>');
            res.slots.forEach(s => select.append(`<option value="${s}">${s}</option>`));
        }
    });
}

$('#convert_dentist_select, #convert_service_select, #convert_appointment_date').on('change', fetchConvertSlots);

$('#convert-form').on('submit', function (e) {
    e.preventDefault();

    $.ajax({
        url: `/leads/requests/${convertRequestId}/convert`,
        method: 'POST',
        data: $(this).serialize(),
        success: function (res) {
            $('#convertModal').modal('hide');
            toastr.success(res.message);
            setTimeout(() => location.reload(), 1000);
        },
        error: function (xhr) {
            if (xhr.status === 422 && xhr.responseJSON.errors) {
                showFormErrors($('#convert-form'), xhr.responseJSON.errors);
            } else {
                toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
            }
        }
    });
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

function approveTestimonial(id) {
    $.post(`/leads/testimonials/${id}/approve`)
        .done(res => { toastr.success(res.message); location.reload(); })
        .fail(() => toastr.error('Could not approve comment.'));
}

function unapproveTestimonial(id) {
    $.post(`/leads/testimonials/${id}/unapprove`)
        .done(res => { toastr.success(res.message); location.reload(); })
        .fail(() => toastr.error('Could not update comment.'));
}

function addTestimonialAsLead(id) {
    $.post(`/leads/testimonials/${id}/add-as-lead`)
        .done(res => { toastr.success(res.message); location.reload(); })
        .fail(() => toastr.error('Could not add as a lead.'));
}

function deleteTestimonial(id) {
    confirmDelete(`/leads/testimonials/${id}`, () => location.reload());
}
</script>
@endpush
