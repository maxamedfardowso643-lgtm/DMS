@extends('layouts.app')

@section('title', 'Patients')
@section('page-title', 'Patients')
@section('breadcrumb')
    <li class="breadcrumb-item active">Patients</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Patients</h3>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#patientModal" onclick="openCreatePatient()">
            <i class="fas fa-plus"></i> Add Patient
        </button>
    </div>
    <div class="card-body">
        <table id="patients-table" class="table table-bordered table-striped w-100">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="patientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="patient-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="patientModalLabel">Add Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="patient_id" id="patient_id">

                    <div class="d-flex align-items-center gap-3 mb-4 p-3" style="background:var(--surface-2);border-radius:var(--radius);">
                        <img id="patient-photo-preview"
                             src="https://ui-avatars.com/api/?background=4f46e5&color=fff&name=Patient"
                             style="width:64px;height:64px;border-radius:14px;object-fit:cover;border:1px solid var(--border);">
                        <div>
                            <label class="form-label mb-1">Photo</label>
                            <input type="file" name="photo" accept="image/*" class="form-control form-control-sm" onchange="previewPatientPhoto(this)">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-control">
                                <option value="">--</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Emergency Contact Name</label>
                            <input type="text" name="emergency_contact_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Emergency Contact Phone</label>
                            <input type="text" name="emergency_contact_phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Medical History</label>
                            <textarea name="medical_history" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Allergies</label>
                            <textarea name="allergies" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="patientViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Patient Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="patientViewBody">
                <div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-muted"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="patientLedgerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header no-print">
                <h5 class="modal-title">Patient Statement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="patientLedgerBody">
                <div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-muted"></i></div>
            </div>
            <div class="modal-footer no-print">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printLedger()"><i class="fas fa-print"></i> Print</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
let patientsTable;

$(function () {
    patientsTable = $('#patients-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: '{{ route('patients.index') }}', type: 'GET' },
        columns: [
            { data: 'patient_code' },
            { data: 'full_name' },
            { data: 'phone', defaultContent: '-' },
            { data: 'email', defaultContent: '-' },
            { data: 'gender', defaultContent: '-' },
            { data: 'is_active', render: d => d ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' },
            {
                data: 'id', orderable: false, searchable: false,
                render: (id, type, row) => `
                    <button class="btn btn-info btn-xs" title="View" onclick="openViewPatient(${id})"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-secondary btn-xs" title="Statement" onclick="openLedger(${id})"><i class="fas fa-file-invoice"></i></button>
                    <button class="btn btn-warning btn-xs" title="Edit" onclick='openEditPatient(${id})'><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-xs" title="Delete" onclick="deletePatient(${id})"><i class="fas fa-trash"></i></button>
                `
            }
        ]
    });
});

function openViewPatient(id) {
    $('#patientViewBody').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-muted"></i></div>');
    $('#patientViewModal').modal('show');
    $.get(`/patients/${id}/quick-view`, function (html) {
        $('#patientViewBody').html(html);
    }).fail(function () {
        $('#patientViewBody').html('<p class="text-danger text-center py-4">Failed to load patient details.</p>');
    });
}

let currentLedgerPatientId = null;

function openLedger(id) {
    currentLedgerPatientId = id;
    $('#patientLedgerBody').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-muted"></i></div>');
    $('#patientLedgerModal').modal('show');
    $.get(`/patients/${id}/ledger-modal`, function (html) {
        $('#patientLedgerBody').html(html);
    }).fail(function () {
        $('#patientLedgerBody').html('<p class="text-danger text-center py-4">Failed to load statement.</p>');
    });
}

function printLedger() {
    if (currentLedgerPatientId) {
        window.open(`/patients/${currentLedgerPatientId}/ledger`, '_blank');
    }
}

function previewPatientPhoto(input) {
    if (input.files && input.files[0]) {
        $('#patient-photo-preview').attr('src', URL.createObjectURL(input.files[0]));
    }
}

function openCreatePatient() {
    $('#patient-form')[0].reset();
    $('#patient_id').val('');
    $('#patient-photo-preview').attr('src', 'https://ui-avatars.com/api/?background=4f46e5&color=fff&name=Patient');
    $('#patientModalLabel').text('Add Patient');
}

function openEditPatient(id) {
    $.get(`/patients/${id}/edit`, function (patient) {
        $('#patient-form')[0].reset();
        $('#patient_id').val(patient.id);
        $.each(patient, function (key, value) {
            $(`#patient-form [name="${key}"]`).val(value);
        });
        $('#patient-photo-preview').attr('src', patient.photo
            ? `/storage/${patient.photo}`
            : 'https://ui-avatars.com/api/?background=4f46e5&color=fff&name=' + encodeURIComponent(patient.first_name + ' ' + patient.last_name));
        $('#patientModalLabel').text('Edit Patient');
        $('#patientModal').modal('show');
    });
}

$('#patient-form').on('submit', function (e) {
    e.preventDefault();
    const id = $('#patient_id').val();
    const url = id ? `/patients/${id}` : '{{ route('patients.store') }}';

    const formData = new FormData(this);
    if (id) formData.append('_method', 'PUT');

    $.ajax({
        url, method: 'POST', data: formData, processData: false, contentType: false,
        success: function (res) {
            $('#patientModal').modal('hide');
            toastr.success(res.message);
            patientsTable.ajax.reload();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                showFormErrors($('#patient-form'), xhr.responseJSON.errors);
            } else {
                toastr.error('Something went wrong.');
            }
        }
    });
});

function deletePatient(id) {
    confirmDelete(`/patients/${id}`, () => patientsTable.ajax.reload());
}
</script>
@endpush
