<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account | {{ \App\Models\Setting::get('clinic_name', config('app.name')) }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
@php
    $clinicName = \App\Models\Setting::get('clinic_name', config('app.name'));
    $clinicLogo = \App\Models\Setting::logo();
@endphp
<div class="login-centered">
    <div class="login-card" style="max-width:480px;">
        <div class="login-logo">
            @if ($clinicLogo)
                <img src="{{ asset('storage/' . $clinicLogo) }}" alt="{{ $clinicName }}">
            @else
                <i class="fas fa-tooth"></i>
            @endif
        </div>
        <h1>{{ $clinicName }}</h1>
        <p class="subtitle">Create your patient account</p>

        @if ($errors->any())
            <div class="alert alert-danger py-2 px-3" style="border-radius:10px;font-size:.85rem;text-align:left;">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-6">
                    <label>First Name</label>
                    <div class="input-wrap">
                        <i class="fas fa-user"></i>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First name" required autofocus>
                    </div>
                </div>
                <div class="col-6">
                    <label>Last Name</label>
                    <div class="input-wrap">
                        <i class="fas fa-user"></i>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last name" required>
                    </div>
                </div>
            </div>

            <label>Email Address</label>
            <div class="input-wrap">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
            </div>

            <label>Phone</label>
            <div class="input-wrap">
                <i class="fas fa-phone"></i>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone number" required>
            </div>

            <div class="row">
                <div class="col-6">
                    <label>Date of Birth</label>
                    <div class="input-wrap">
                        <i class="fas fa-cake-candles"></i>
                        @include('partials.dob-input', ['value' => old('date_of_birth')])
                    </div>
                </div>
                <div class="col-6">
                    <label>Gender</label>
                    <div class="input-wrap">
                        <i class="fas fa-venus-mars"></i>
                        <select name="gender" class="form-control" style="padding-left:2.6rem;border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--surface-2);">
                            <option value="">Select</option>
                            <option value="male" @selected(old('gender') === 'male')>Male</option>
                            <option value="female" @selected(old('gender') === 'female')>Female</option>
                        </select>
                    </div>
                </div>
            </div>

            <label>Address</label>
            <div class="input-wrap">
                <i class="fas fa-location-dot"></i>
                <input type="text" name="address" value="{{ old('address') }}" placeholder="Address (optional)">
            </div>

            <label>Password</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="At least 8 characters" required>
            </div>

            <label>Confirm Password</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" name="password_confirmation" placeholder="Re-enter password" required>
            </div>

            <button type="submit" class="btn-login">Create Account <i class="fas fa-arrow-right ms-1"></i></button>
        </form>

        <a href="{{ route('login') }}" class="back-to-site"><i class="fas fa-arrow-left me-1"></i> Already have an account? Sign in</a>
    </div>
</div>
</body>
</html>
