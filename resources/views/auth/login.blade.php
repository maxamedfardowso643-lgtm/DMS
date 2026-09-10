<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In | {{ \App\Models\Setting::get('clinic_name', config('app.name')) }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
@php
    $clinicName = \App\Models\Setting::get('clinic_name', config('app.name'));
    $clinicLogo = \App\Models\Setting::get('clinic_logo');
@endphp
<div class="login-centered">
    <div class="login-card">
        <div class="login-logo">
            @if ($clinicLogo)
                <img src="{{ asset('storage/' . $clinicLogo) }}" alt="{{ $clinicName }}">
            @else
                <i class="fas fa-tooth"></i>
            @endif
        </div>
        <h1>{{ $clinicName }}</h1>
        <p class="subtitle">Sign in to continue to your dashboard</p>

        @if ($errors->any())
            <div class="alert alert-danger py-2 px-3" style="border-radius:10px;font-size:.85rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <label>Email Address</label>
            <div class="input-wrap">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@clinic.com" required autofocus>
            </div>

            <label>Password</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3" style="font-size:.82rem;">
                <label class="d-flex align-items-center gap-2" style="color:var(--text-muted);">
                    <input type="checkbox" name="remember" style="margin-right:.4rem;"> Remember me
                </label>
            </div>

            <button type="submit" class="btn-login">Sign In <i class="fas fa-arrow-right ms-1"></i></button>
        </form>

        <a href="{{ route('public.home') }}" class="back-to-site"><i class="fas fa-arrow-left me-1"></i> Back to website</a>
    </div>
</div>
</body>
</html>
