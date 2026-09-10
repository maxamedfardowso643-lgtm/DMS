@extends('layouts.app')

@section('title', 'Roles')
@section('page-title', 'Roles')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">Roles</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">System Roles</h3></div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead><tr><th>Name</th><th>Slug</th><th>Description</th><th>Users</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td><code>{{ $role->slug }}</code></td>
                        <td>{{ $role->description }}</td>
                        <td>{{ $role->users_count }}</td>
                        <td><button class="btn btn-warning btn-xs" onclick='openEditRole({{ $role->id }})'><i class="fas fa-edit"></i></button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="role-form">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="role_id">
                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" id="role_name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Description</label><textarea id="role_description" class="form-control" rows="2"></textarea></div>
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
function openEditRole(id) {
    $.get(`/roles/${id}/edit`, function (r) {
        $('#role_id').val(r.id);
        $('#role_name').val(r.name);
        $('#role_description').val(r.description);
        $('#roleModal').modal('show');
    });
}

$('#role-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: `/roles/${$('#role_id').val()}`, method: 'PUT',
        data: { name: $('#role_name').val(), description: $('#role_description').val() },
        success: function (res) { toastr.success(res.message); location.reload(); },
        error: function () { toastr.error('Something went wrong.'); }
    });
});
</script>
@endpush
