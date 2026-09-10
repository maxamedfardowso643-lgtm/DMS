@extends('layouts.app')

@section('title', 'Treatments')
@section('page-title', 'Treatment Records')
@section('breadcrumb')
    <li class="breadcrumb-item active">Treatments</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Treatment Records</h3>
        <button class="btn btn-primary btn-sm" onclick="openCreateTreatment()"><i class="fas fa-plus"></i> Record Treatment</button>
    </div>
    <div class="card-body">
        <table id="treatments-table" class="table table-bordered table-striped w-100">
            <thead><tr><th>Date</th><th>Patient</th><th>Dentist</th><th>Diagnosis</th><th>Actions</th></tr></thead>
        </table>
    </div>
</div>

<div class="modal fade" id="treatmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="treatment-form">
                <div class="modal-header">
                    <h5 class="modal-title">Record Treatment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Patient *</label>
                        <div class="patient-search-box">
                            <input type="text" id="trt_patient_search" class="form-control" placeholder="Search by name, ID, mobile or emergency contact..." autocomplete="off" required>
                            <div class="search-results" id="trt_patient_results"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Completed Appointment *</label>
                        <select name="appointment_id" id="trt_appointment_select" class="form-control" required disabled>
                            <option value="">Search &amp; select a patient first</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Diagnosis</label><textarea name="diagnosis" class="form-control" rows="2"></textarea></div>
                    <div class="mb-3"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2"></textarea></div>

                    <label class="form-label">Dental Chart (click a tooth to mark condition)</label>
                    <div class="tooth-chart mb-2">
                        @php
                            $upper = ['18','17','16','15','14','13','12','11','21','22','23','24','25','26','27','28'];
                            $lower = ['48','47','46','45','44','43','42','41','31','32','33','34','35','36','37','38'];
                        @endphp
                        @foreach ($upper as $tooth)
                            <div class="tooth healthy" data-tooth="{{ $tooth }}" onclick="cycleTooth(this)">{{ $tooth }}</div>
                        @endforeach
                        <div class="w-100"></div>
                        @foreach ($lower as $tooth)
                            <div class="tooth healthy" data-tooth="{{ $tooth }}" onclick="cycleTooth(this)">{{ $tooth }}</div>
                        @endforeach
                    </div>
                    <small class="text-muted">Click a tooth repeatedly to cycle through conditions: healthy → decayed → filled → missing → crowned → root canal → implant → extracted → impacted</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Treatment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
let treatmentsTable;
const conditions = ['healthy','decayed','filled','missing','crowned','root_canal','implant','extracted','impacted'];

$(function () {
    treatmentsTable = $('#treatments-table').DataTable({
        processing: true, serverSide: true,
        ajax: { url: '{{ route('treatments.index') }}', type: 'GET' },
        columns: [
            { data: 'visit_date' }, { data: 'patient' }, { data: 'dentist' }, { data: 'diagnosis', defaultContent: '-' },
            { data: 'id', orderable: false, searchable: false, render: id => `
                <a href="/treatments/${id}" class="btn btn-info btn-xs"><i class="fas fa-eye"></i></a>
                <button class="btn btn-danger btn-xs" onclick="deleteTreatment(${id})"><i class="fas fa-trash"></i></button>
            `}
        ]
    });

    initPatientSearch('#trt_patient_search', '#trt_patient_results', function (patient) {
        loadPatientAppointments(patient.id);
    });
});

function loadPatientAppointments(patientId) {
    const select = $('#trt_appointment_select');
    select.prop('disabled', true).empty().append('<option value="">Loading...</option>');

    $.get('{{ route('treatments.patient-appointments') }}', { patient_id: patientId }, function (appointments) {
        select.empty();
        if (!appointments.length) {
            select.append('<option value="">No completed appointments awaiting treatment for this patient</option>');
        } else {
            select.append('<option value="">Select appointment</option>');
            appointments.forEach(a => select.append(`<option value="${a.id}">${a.label}</option>`));
            select.prop('disabled', false);
        }
    });
}

function cycleTooth(el) {
    const current = conditions.indexOf($(el).data('current') || 'healthy');
    const next = conditions[(current + 1) % conditions.length];
    $(el).removeClass(conditions.join(' ')).addClass(next).data('current', next);
}

function openCreateTreatment() {
    $('#treatment-form')[0].reset();
    $('#trt_patient_results').hide().empty();
    $('#trt_appointment_select').prop('disabled', true).empty().append('<option value="">Search &amp; select a patient first</option>');
    $('.tooth').removeClass(conditions.join(' ')).addClass('healthy').data('current', 'healthy');
    $('#treatmentModal').modal('show');
}

$('#treatment-form').on('submit', function (e) {
    e.preventDefault();
    const formData = $(this).serializeArray().reduce((acc, x) => { acc[x.name] = x.value; return acc; }, {});
    formData.tooth_conditions = {};
    $('.tooth').each(function () {
        const cond = $(this).data('current');
        if (cond && cond !== 'healthy') formData.tooth_conditions[$(this).data('tooth')] = cond;
    });

    $.ajax({
        url: '{{ route('treatments.store') }}', method: 'POST', data: formData,
        success: function (res) {
            $('#treatmentModal').modal('hide');
            toastr.success(res.message);
            treatmentsTable.ajax.reload();
        },
        error: function (xhr) {
            toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
        }
    });
});

function deleteTreatment(id) {
    confirmDelete(`/treatments/${id}`, () => treatmentsTable.ajax.reload());
}
</script>
@endpush
