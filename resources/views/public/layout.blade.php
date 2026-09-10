<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Home') | {{ \App\Models\Setting::get('clinic_name', config('app.name')) }}</title>
    <meta name="description" content="{{ \App\Models\Setting::get('clinic_name', config('app.name')) }} — modern dental care you can trust.">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    @stack('css')
</head>
<body class="public-body">

@php
    $clinicName = \App\Models\Setting::get('clinic_name', config('app.name'));
    $clinicLogo = \App\Models\Setting::get('clinic_logo');
    $clinicPhone = \App\Models\Setting::get('clinic_phone');
@endphp

<nav class="navbar navbar-expand-lg pb-navbar py-3">
    <div class="container">
        <a class="brand" href="{{ route('public.home') }}">
            <span class="mark">
                @if ($clinicLogo)
                    <img src="{{ asset('storage/' . $clinicLogo) }}" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
                @else
                    <i class="fas fa-tooth"></i>
                @endif
            </span>
            {{ $clinicName }}
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#pbNav">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="pbNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }}" href="{{ route('public.home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}" href="{{ route('public.about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.services') ? 'active' : '' }}" href="{{ route('public.services') }}">Services</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.team') ? 'active' : '' }}" href="{{ route('public.team') }}">Our Team</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.gallery') ? 'active' : '' }}" href="{{ route('public.gallery') }}">Gallery</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}" href="{{ route('public.contact') }}">Contact</a></li>
            </ul>
            <div class="d-flex gap-2">
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Staff Login</a>
                <a href="{{ route('public.contact') }}" class="btn btn-primary btn-sm">Book Appointment</a>
            </div>
        </div>
    </div>
</nav>

@yield('content')

<footer class="pb-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="brand">
                    <span class="mark">
                        @if ($clinicLogo)
                            <img src="{{ asset('storage/' . $clinicLogo) }}" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
                        @else
                            <i class="fas fa-tooth"></i>
                        @endif
                    </span>
                    {{ $clinicName }}
                </div>
                <p style="font-size:.88rem;max-width:320px;">{{ \App\Models\Setting::get('clinic_address', 'Providing modern, compassionate dental care for your whole family.') }}</p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <h6>Quick Links</h6>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('public.about') }}">About Us</a>
                    <a href="{{ route('public.services') }}">Services</a>
                    <a href="{{ route('public.team') }}">Our Team</a>
                    <a href="{{ route('public.gallery') }}">Gallery</a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <h6>Support</h6>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('public.contact') }}">Contact Us</a>
                    <a href="{{ route('login') }}">Staff / Patient Login</a>
                </div>
            </div>
            <div class="col-lg-3">
                <h6>Contact Info</h6>
                <div class="d-flex flex-column gap-2">
                    <span><i class="fas fa-phone-alt me-2"></i>{{ $clinicPhone ?? 'N/A' }}</span>
                    <span><i class="fas fa-envelope me-2"></i>{{ \App\Models\Setting::get('clinic_email', 'N/A') }}</span>
                    <span><i class="fas fa-clock me-2"></i>{{ \App\Models\Setting::get('working_hours', 'N/A') }}</span>
                </div>
            </div>
        </div>
        <div class="bottom d-flex justify-content-between flex-wrap gap-2">
            <span>&copy; {{ date('Y') }} {{ $clinicName }}. All rights reserved.</span>
            <span>Powered by DCATMS</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
</script>
@stack('js')
</body>
</html>
