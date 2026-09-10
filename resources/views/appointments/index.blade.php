@extends('layouts.app')

@section('title', 'Appointments')
@section('page-title', 'Appointments')
@section('breadcrumb')
    <li class="breadcrumb-item active">Appointments</li>
@endsection

@section('content')
<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#calendar-view">Calendar</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#list-view">List</a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="calendar-view">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Appointment Calendar</h3>
                <button class="btn btn-primary btn-sm" onclick="openBookModal()"><i class="fas fa-plus"></i> Book Appointment</button>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <div class="tab-pane" id="list-view">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">All Appointments</h3>
                <button class="btn btn-primary btn-sm" onclick="openBookModal()"><i class="fas fa-plus"></i> Book Appointment</button>
            </div>
            <div class="card-body">
                <table id="appointments-table" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>No.</th><th>Patient</th><th>Dentist</th><th>Service</th>
                            <th>Date</th><th>Time</th><th>Status</th><th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="appointment-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookModalLabel">Book Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="appointment_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Patient *</label>
                            <div class="patient-search-box">
                                <input type="text" id="apt_patient_search" class="form-control" placeholder="Search by name, ID, mobile or emergency contact..." autocomplete="off" required>
                                <input type="hidden" name="patient_id" id="apt_patient_id">
                                <div class="search-results" id="apt_patient_results"></div>
                            </div>
                            <small class="text-muted" id="apt_patient_meta"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dentist *</label>
                            <select name="dentist_id" id="dentist_select" class="form-control" required>
                                <option value="">Select dentist</option>
                                @foreach ($dentists as $d)
                                    <option value="{{ $d->id }}">{{ $d->user->name }} ({{ $d->specialization }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Service *</label>
                            <select name="service_id" id="service_select" class="form-control" required>
                                <option value="">Select service</option>
                                @foreach ($services as $s)
                                    <option value="{{ $s->id }}" data-duration="{{ $s->duration_minutes }}">{{ $s->name }} ({{ $s->duration_minutes }} min)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Source</label>
                            <select name="source" class="form-control">
                                <option value="scheduled">Scheduled</option>
                                <option value="walk_in">Walk-in</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Date *</label>
                            <input type="date" name="appointment_date" id="appointment_date" class="form-control" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Available Time Slots *</label>
                            <select name="start_time" id="slots_select" class="form-control" required>
                                <option value="">Select dentist, service &amp; date first</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="1"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Status Change Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="status-form">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="status_appointment_id">
                    <div class="mb-3">
                        <label class="form-label">New Status</label>
                        <select id="status_select" class="form-control">
                            <option value="booked">Booked</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="checked_in">Checked-in</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="no_show">No-show</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea id="status_remarks" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.11/main.min.css">
@endpush

@push('js')
<script>
let appointmentsTable, calendar;

$(function () {
    appointmentsTable = $('#appointments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: '{{ route('appointments.index') }}', type: 'GET' },
        columns: [
            { data: 'appointment_no' },
            { data: 'patient' },
            { data: 'dentist' },
            { data: 'service' },
            { data: 'appointment_date' },
            { data: 'start_time' },
            { data: null, render: r => `<span class="badge bg-${r.status_color}">${r.status.replace('_',' ')}</span>` },
            {
                data: 'id', orderable: false, searchable: false,
                render: (id) => `
                    <a href="/appointments/${id}" class="btn btn-info btn-xs"><i class="fas fa-eye"></i></a>
                    <button class="btn btn-secondary btn-xs" onclick='openStatusModal(${id})'><i class="fas fa-sync"></i></button>
                    <button class="btn btn-danger btn-xs" onclick="deleteAppointment(${id})"><i class="fas fa-trash"></i></button>
                `
            }
        ]
    });

    const calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
        editable: true,
        events: function (info, success, failure) {
            $.get('{{ route('appointments.calendar-events') }}', { start: info.startStr, end: info.endStr }, success).fail(failure);
        },
        eventDrop: function (info) {
            $.post(`/appointments/${info.event.id}/reschedule`, {
                appointment_date: info.event.startStr.substring(0, 10),
                start_time: info.event.startStr.substring(11, 16),
            }).done(res => { toastr.success(res.message); calendar.refetchEvents(); appointmentsTable.ajax.reload(); })
              .fail(() => { toastr.error('Could not reschedule (conflict).'); info.revert(); });
        },
        eventClick: function (info) {
            window.location.href = `/appointments/${info.event.id}`;
        }
    });
    calendar.render();

    initPatientSearch('#apt_patient_search', '#apt_patient_results', function (patient) {
        $('#apt_patient_id').val(patient.id);
        $('#apt_patient_meta').text(patient.meta);
    });
});

function openBookModal() {
    $('#appointment-form')[0].reset();
    $('#appointment_id').val('');
    $('#apt_patient_id').val('');
    $('#apt_patient_meta').text('');
    $('#bookModalLabel').text('Book Appointment');
    $('#bookModal').modal('show');
}

function fetchSlots() {
    const dentist_id = $('#dentist_select').val();
    const service_id = $('#service_select').val();
    const date = $('#appointment_date').val();
    if (!dentist_id || !service_id || !date) return;

    $.get('{{ route('appointments.available-slots') }}', { dentist_id, service_id, date }, function (res) {
        const select = $('#slots_select');
        select.empty();
        if (res.slots.length === 0) {
            select.append(`<option value="">${res.message || 'No slots available'}</option>`);
        } else {
            select.append('<option value="">Select a time</option>');
            res.slots.forEach(s => select.append(`<option value="${s}">${s}</option>`));
        }
    });
}

$('#dentist_select, #service_select, #appointment_date').on('change', fetchSlots);

$('#appointment-form').on('submit', function (e) {
    e.preventDefault();
    if (!$('#apt_patient_id').val()) {
        toastr.error('Please select a patient from the search results.');
        return;
    }
    const id = $('#appointment_id').val();
    const url = id ? `/appointments/${id}` : '{{ route('appointments.store') }}';
    const method = id ? 'PUT' : 'POST';

    $.ajax({
        url, method, data: $(this).serialize(),
        success: function (res) {
            $('#bookModal').modal('hide');
            toastr.success(res.message);
            appointmentsTable.ajax.reload();
            calendar.refetchEvents();
        },
        error: function (xhr) {
            if (xhr.status === 422 && xhr.responseJSON.errors) {
                showFormErrors($('#appointment-form'), xhr.responseJSON.errors);
            } else {
                toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
            }
        }
    });
});

function openStatusModal(id) {
    $('#status_appointment_id').val(id);
    $('#statusModal').modal('show');
}

$('#status-form').on('submit', function (e) {
    e.preventDefault();
    const id = $('#status_appointment_id').val();
    $.post(`/appointments/${id}/status`, {
        status: $('#status_select').val(),
        remarks: $('#status_remarks').val(),
    }).done(res => {
        $('#statusModal').modal('hide');
        toastr.success(res.message);
        appointmentsTable.ajax.reload();
        calendar.refetchEvents();
    }).fail(() => toastr.error('Could not update status.'));
});

function deleteAppointment(id) {
    confirmDelete(`/appointments/${id}`, () => { appointmentsTable.ajax.reload(); calendar.refetchEvents(); });
}
</script>
@endpush
