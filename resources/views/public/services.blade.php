@extends('public.layout')

@section('title', 'Services')

@section('content')
<div class="pb-page-header">
    <div class="container">
        <h1>Our Services</h1>
        <p>Comprehensive dental care for every stage of life.</p>
    </div>
</div>

<section class="pb-section">
    <div class="container">
        @php $pool = ['dental-room-1','dentist-xray-film','dental-chair-orange','clinic-reception','surgical-team-1','hero-dentist-xray','surgical-team-2','doctor-patient-talk']; $ci = 0; @endphp
        @forelse ($services as $category => $group)
            <div class="mb-5">
                <h3 class="fw-bold mb-4 text-capitalize"><i class="fas fa-tooth text-primary me-2"></i>{{ $category ?: 'General' }}</h3>
                <div class="row g-4">
                    @foreach ($group as $i => $service)
                        <div class="col-md-6 col-lg-4">
                            <div class="pb-service-card">
                                <img src="{{ asset('images/stock/' . $pool[$ci++ % count($pool)] . '.jpg') }}" alt="{{ $service->name }}">
                                <div class="body">
                                    <h5>{{ $service->name }}</h5>
                                    @if ($service->description)
                                        <p class="text-muted" style="font-size:.85rem;">{{ $service->description }}</p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div class="meta"><i class="far fa-clock me-1"></i>{{ $service->duration_minutes }} min</div>
                                        <div class="price">${{ number_format($service->price, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">Our service catalogue is being updated. Please check back soon.</div>
        @endforelse

        <div class="pb-cta text-center mt-5">
            <div class="content">
                <h2 class="mb-3">Not sure which treatment you need?</h2>
                <p class="mb-4" style="color:rgba(255,255,255,.85);">Reach out and our team will help you find the right care.</p>
                <a href="{{ route('public.contact') }}" class="btn btn-light btn-lg fw-bold px-5">Get In Touch</a>
            </div>
        </div>
    </div>
</section>
@endsection
