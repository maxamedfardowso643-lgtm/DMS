@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('breadcrumb')
    <li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <img id="avatar-preview"
                     src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://ui-avatars.com/api/?background=4f46e5&color=fff&size=128&name=' . urlencode($user->name) }}"
                     style="width:110px;height:110px;border-radius:50%;object-fit:cover;border:3px solid var(--border);">
                <h5 class="mt-3 mb-0 fw-bold">{{ $user->name }}</h5>
                <p class="text-muted mb-2" style="font-size:.85rem;">{{ $user->email }}</p>
                <span class="badge bg-primary text-capitalize">{{ $user->roles->first()->name ?? 'User' }}</span>
                <hr>
                <p class="text-muted mb-0" style="font-size:.78rem;">Member since {{ $user->created_at->format('M Y') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Account Information</h3></div>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center gap-3">
                        <div>
                            <label class="form-label">Profile Photo</label>
                            <input type="file" name="photo" accept="image/*" class="form-control form-control-sm" onchange="previewAvatar(this)">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger py-2" style="border-radius:10px;font-size:.85rem;">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header"><h3 class="card-title">Change Password</h3></div>
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Current Password *</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">New Password *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Confirm New Password *</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        $('#avatar-preview').attr('src', URL.createObjectURL(input.files[0]));
    }
}
</script>
@endpush
