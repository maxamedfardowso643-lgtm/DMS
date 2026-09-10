@extends('public.layout')

@section('title', 'About Us')

@section('content')
<div class="pb-page-header">
    <div class="container">
        <h1>About Us</h1>
        <p>Get to know the story and people behind {{ \App\Models\Setting::get('clinic_name', config('app.name')) }}.</p>
    </div>
</div>

<section class="pb-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="about-logo-panel">
                    @php $logo = \App\Models\Setting::get('clinic_logo'); @endphp
                    @if ($logo)
                        <img src="{{ asset('storage/' . $logo) }}" alt="{{ \App\Models\Setting::get('clinic_name', config('app.name')) }}">
                    @else
                        <i class="fas fa-tooth"></i>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="pb-eyebrow">OUR STORY</div>
                <h2>Caring For Smiles Since Day One</h2>
                <p class="sub">{{ \App\Models\Setting::get('clinic_name', config('app.name')) }} was founded with a simple mission: make world-class dental care accessible, comfortable, and transparent for every patient who walks through our doors.</p>
                <p class="sub">Today, our team of specialists uses modern equipment and evidence-based techniques to treat everything from routine check-ups to complex restorative procedures — always with a gentle, patient-first approach.</p>
                <div class="row mt-4 g-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2"><i class="fas fa-check-circle text-primary"></i><span class="fw-semibold">Modern Equipment</span></div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2"><i class="fas fa-check-circle text-primary"></i><span class="fw-semibold">Certified Specialists</span></div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2"><i class="fas fa-check-circle text-primary"></i><span class="fw-semibold">Flexible Scheduling</span></div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2"><i class="fas fa-check-circle text-primary"></i><span class="fw-semibold">Family Friendly</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pb-section bg-soft">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="pb-card text-center">
                    <div class="icon mx-auto"><i class="fas fa-bullseye"></i></div>
                    <h5>Our Mission</h5>
                    <p>To deliver compassionate, high-quality dental care that improves lives, one smile at a time.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pb-card text-center">
                    <div class="icon mx-auto"><i class="fas fa-eye"></i></div>
                    <h5>Our Vision</h5>
                    <p>To be the most trusted dental clinic in the region, known for excellence and integrity.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pb-card text-center">
                    <div class="icon mx-auto"><i class="fas fa-heart"></i></div>
                    <h5>Our Values</h5>
                    <p>Compassion, transparency, and continuous learning guide everything we do.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@if ($dentists->isNotEmpty())
<section class="pb-section">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width:640px;">
            <div class="pb-eyebrow">OUR DOCTORS</div>
            <h2>Meet the Specialists</h2>
        </div>
        <div class="row g-4">
            @foreach ($dentists as $dentist)
                <div class="col-md-6 col-lg-3">
                    <div class="pb-team-card">
                        <img src="{{ $dentist->user->photo ? asset('storage/' . $dentist->user->photo) : 'https://ui-avatars.com/api/?background=4f46e5&color=fff&size=400&name=' . urlencode($dentist->user->name) }}" alt="{{ $dentist->user->name }}">
                        <div class="body">
                            <h5>{{ $dentist->user->name }}</h5>
                            <div class="role">{{ $dentist->specialization ?? 'General Dentist' }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
