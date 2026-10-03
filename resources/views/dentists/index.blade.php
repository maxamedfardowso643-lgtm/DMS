@extends('layouts.app')

@section('title', 'Dentists')
@section('page-title', 'Dentists')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dentists</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Dentists</h3>
        <button class="btn btn-primary btn-sm" onclick="openCreateDentist()"><i class="fas fa-plus"></i> Add Dentist</button>
    </div>
    <div class="card-body">
        <table id="dentists-table" class="table table-bordered table-striped w-100">
            <thead><tr><th></th><th>Code</th><th>Name</th><th>Email</th><th>Specialization</th><th>Status</th><th>Actions</th></tr></thead>
        </table>
    </div>
</div>

<div class="modal fade" id="dentistModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="dentist-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="dentistModalLabel">Add Dentist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="dentist_id" id="dentist_id">

                    <div class="d-flex align-items-center gap-3 mb-4 p-3" style="background:var(--surface-2);border-radius:var(--radius);">
                        <img id="dentist-photo-preview"
                             src="https://ui-avatars.com/api/?background=4f46e5&color=fff&name=Dentist"
                             style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:1px solid var(--border);">
                        <div>
                            <label class="form-label mb-1">Photo</label>
                            <input type="file" name="photo" accept="image/*" class="form-control form-control-sm" onchange="previewDentistPhoto(this)">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Password <span id="pw-hint" class="text-muted small"></span></label><input type="password" name="password" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Specialization</label><input type="text" name="specialization" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">License Number</label><input type="text" name="license_number" class="form-control"></div>
                        <div class="col-md-12 mb-1"><label class="form-label">Bio</label><textarea name="bio" class="form-control" rows="2"></textarea></div>
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

<div class="modal fade" id="dentistViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dentist Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="dentistViewBody">
                <div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-muted"></i></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
let dentistsTable;

function avatarUrl(name, photo) {
    if (photo && photo.startsWith("images/")) return `/${photo}`;
    return photo ? `/storage/${photo}` : `https://ui-avatars.com/api/?background=4f46e5&color=fff&name=${encodeURIComponent(name)}`;
}

$(function () {
    dentistsTable = $('#dentists-table').DataTable({
        processing: true, serverSide: true,
        ajax: { url: '{{ route('dentists.index') }}', type: 'GET' },
        columns: [
            { data: null, orderable: false, searchable: false, render: (row) => `<img src="${avatarUrl(row.name, row.photo)}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">` },
            { data: 'dentist_code' }, { data: 'name' }, { data: 'email' }, { data: 'specialization', defaultContent: '-' },
            { data: 'is_active', render: d => d ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' },
            { data: 'id', orderable: false, searchable: false, render: id => `
                <button class="btn btn-info btn-xs" title="View" onclick="openViewDentist(${id})"><i class="fas fa-eye"></i></button>
                <button class="btn btn-warning btn-xs" title="Edit" onclick='openEditDentist(${id})'><i class="fas fa-edit"></i></button>
                <button class="btn btn-danger btn-xs" title="Delete" onclick="deleteDentist(${id})"><i class="fas fa-trash"></i></button>
            `}
        ]
    });
});

function openViewDentist(id) {
    $('#dentistViewBody').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-muted"></i></div>');
    $('#dentistViewModal').modal('show');
    $.get(`/dentists/${id}/quick-view`, function (html) {
        $('#dentistViewBody').html(html);
    }).fail(function () {
        $('#dentistViewBody').html('<p class="text-danger text-center py-4">Failed to load dentist details.</p>');
    });
}

function previewDentistPhoto(input) {
    if (input.files && input.files[0]) {
        $('#dentist-photo-preview').attr('src', URL.createObjectURL(input.files[0]));
    }
}

function openCreateDentist() {
    $('#dentist-form')[0].reset();
    $('#dentist_id').val('');
    $('#dentist-form [name="password"]').prop('required', true);
    $('#pw-hint').text('');
    $('#dentist-photo-preview').attr('src', 'https://ui-avatars.com/api/?background=4f46e5&color=fff&name=Dentist');
    $('#dentistModalLabel').text('Add Dentist');
    $('#dentistModal').modal('show');
}

function openEditDentist(id) {
    $.get(`/dentists/${id}/edit`, function (d) {
        $('#dentist-form')[0].reset();
        $('#dentist_id').val(d.id);
        $.each(d, (k, v) => $(`#dentist-form [name="${k}"]`).val(v));
        $('#dentist-form [name="password"]').prop('required', false);
        $('#pw-hint').text('(leave blank to keep current)');
        $('#dentist-photo-preview').attr('src', avatarUrl(d.name, d.photo));
        $('#dentistModalLabel').text('Edit Dentist');
        $('#dentistModal').modal('show');
    });
}

$('#dentist-form').on('submit', function (e) {
    e.preventDefault();
    const id = $('#dentist_id').val();
    const url = id ? `/dentists/${id}` : '{{ route('dentists.store') }}';

    const formData = new FormData(this);
    if (id) formData.append('_method', 'PUT');

    $.ajax({
        url, method: 'POST', data: formData, processData: false, contentType: false,
        success: function (res) {
            $('#dentistModal').modal('hide');
            toastr.success(res.message);
            dentistsTable.ajax.reload();
        },
        error: function (xhr) {
            if (xhr.status === 422) showFormErrors($('#dentist-form'), xhr.responseJSON.errors);
            else toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
        }
    });
});

function deleteDentist(id) {
    confirmDelete(`/dentists/${id}`, () => dentistsTable.ajax.reload());
}
</script>
@endpush
