@extends('layouts.app')

@section('title', 'Users')
@section('page-title', 'Users &amp; Roles')
@section('breadcrumb')
    <li class="breadcrumb-item active">Users</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">System Users</h3>
        <div>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-user-tag"></i> Manage Roles</a>
            <button class="btn btn-primary btn-sm" onclick="openCreateUser()"><i class="fas fa-plus"></i> Add User</button>
        </div>
    </div>
    <div class="card-body">
        <table id="users-table" class="table table-bordered table-striped w-100">
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
        </table>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="user-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Password <span id="pw-hint" class="text-muted small"></span></label><input type="password" name="password" class="form-control"></div>
                    <div class="mb-3">
                        <label class="form-label">Role *</label>
                        <select name="role_id" class="form-control" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-check" id="active-field" style="display:none;">
                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Active</label>
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
@endsection

@push('js')
<script>
let usersTable;

$(function () {
    usersTable = $('#users-table').DataTable({
        processing: true, serverSide: true,
        ajax: { url: '{{ route('users.index') }}', type: 'GET' },
        columns: [
            { data: 'name' }, { data: 'email' }, { data: 'roles', defaultContent: '-' },
            { data: 'is_active', render: d => d ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' },
            { data: null, orderable: false, searchable: false, render: row => `
                <button class="btn btn-warning btn-xs" onclick='openEditUser(${row.id})'><i class="fas fa-edit"></i></button>
                ${row.is_active
                    ? `<button class="btn btn-secondary btn-xs" onclick="toggleUserStatus(${row.id})" title="Deactivate"><i class="fas fa-user-lock"></i></button>`
                    : `<button class="btn btn-success btn-xs" onclick="toggleUserStatus(${row.id})" title="Activate"><i class="fas fa-user-check"></i></button>`}
                <button class="btn btn-danger btn-xs" onclick="deleteUser(${row.id})"><i class="fas fa-trash"></i></button>
            `}
        ]
    });
});

function openCreateUser() {
    $('#user-form')[0].reset();
    $('#user_id').val('');
    $('#user-form [name="password"]').prop('required', true);
    $('#pw-hint').text('');
    $('#active-field').hide();
    $('#userModalLabel').text('Add User');
    $('#userModal').modal('show');
}

function openEditUser(id) {
    $.get(`/users/${id}/edit`, function (u) {
        $('#user-form')[0].reset();
        $('#user_id').val(u.id);
        $.each(u, (k, v) => {
            if (k === 'is_active') $('#is_active').prop('checked', !!v);
            else $(`#user-form [name="${k}"]`).val(v);
        });
        $('#user-form [name="password"]').prop('required', false);
        $('#pw-hint').text('(leave blank to keep current)');
        $('#active-field').show();
        $('#userModalLabel').text('Edit User');
        $('#userModal').modal('show');
    });
}

$('#user-form').on('submit', function (e) {
    e.preventDefault();
    const id = $('#user_id').val();
    const url = id ? `/users/${id}` : '{{ route('users.store') }}';
    const method = id ? 'PUT' : 'POST';
    $.ajax({
        url, method, data: $(this).serialize(),
        success: function (res) {
            $('#userModal').modal('hide');
            toastr.success(res.message);
            usersTable.ajax.reload();
        },
        error: function (xhr) {
            if (xhr.status === 422) showFormErrors($('#user-form'), xhr.responseJSON.errors);
            else toastr.error('Something went wrong.');
        }
    });
});

function deleteUser(id) {
    confirmDelete(`/users/${id}`, () => usersTable.ajax.reload());
}

function toggleUserStatus(id) {
    $.ajax({
        url: `/users/${id}/toggle-status`,
        type: 'POST',
        success: function (res) {
            toastr.success(res.message || 'User status updated');
            usersTable.ajax.reload(null, false);
        },
        error: function (xhr) {
            toastr.error(xhr.responseJSON?.message || 'Could not update user status');
        },
    });
}
</script>
@endpush
