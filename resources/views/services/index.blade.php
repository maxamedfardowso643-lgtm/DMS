@extends('layouts.app')

@section('title', 'Services')
@section('page-title', 'Service Catalogue')
@section('breadcrumb')
    <li class="breadcrumb-item active">Services</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Services</h3>
        <button class="btn btn-primary btn-sm" onclick="openCreateService()"><i class="fas fa-plus"></i> Add Service</button>
    </div>
    <div class="card-body">
        <table id="services-table" class="table table-bordered table-striped w-100">
            <thead><tr><th>Name</th><th>Category</th><th>Price</th><th>Duration (min)</th><th>Status</th><th>Actions</th></tr></thead>
        </table>
    </div>
</div>

<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="service-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="serviceModalLabel">Add Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="service_id" id="service_id">
                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Category</label><input type="text" name="category" class="form-control"></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Price *</label><input type="number" step="0.01" name="price" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Duration (min) *</label><input type="number" name="duration_minutes" class="form-control" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
let servicesTable;

$(function () {
    servicesTable = $('#services-table').DataTable({
        processing: true, serverSide: true,
        ajax: { url: '{{ route('services.index') }}', type: 'GET' },
        columns: [
            { data: 'name' }, { data: 'category', defaultContent: '-' }, { data: 'price' }, { data: 'duration_minutes' },
            { data: 'is_active', render: d => d ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' },
            { data: 'id', orderable: false, searchable: false, render: id => `
                <button class="btn btn-warning btn-xs" onclick='openEditService(${id})'><i class="fas fa-edit"></i></button>
                <button class="btn btn-danger btn-xs" onclick="deleteService(${id})"><i class="fas fa-trash"></i></button>
            `}
        ]
    });
});

function openCreateService() {
    $('#service-form')[0].reset();
    $('#service_id').val('');
    $('#serviceModalLabel').text('Add Service');
    $('#serviceModal').modal('show');
}

function openEditService(id) {
    $.get(`/services/${id}/edit`, function (s) {
        $('#service-form')[0].reset();
        $('#service_id').val(s.id);
        $.each(s, (k, v) => $(`#service-form [name="${k}"]`).val(v));
        $('#serviceModalLabel').text('Edit Service');
        $('#serviceModal').modal('show');
    });
}

$('#service-form').on('submit', function (e) {
    e.preventDefault();
    const id = $('#service_id').val();
    const url = id ? `/services/${id}` : '{{ route('services.store') }}';
    const method = id ? 'PUT' : 'POST';
    $.ajax({
        url, method, data: $(this).serialize(),
        success: function (res) {
            $('#serviceModal').modal('hide');
            toastr.success(res.message);
            servicesTable.ajax.reload();
        },
        error: function (xhr) {
            if (xhr.status === 422) showFormErrors($('#service-form'), xhr.responseJSON.errors);
            else toastr.error('Something went wrong.');
        }
    });
});

function deleteService(id) {
    confirmDelete(`/services/${id}`, () => servicesTable.ajax.reload());
}
</script>
@endpush
