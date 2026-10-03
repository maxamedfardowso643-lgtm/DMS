@extends('public.layout')

@section('title', 'Home')

@section('content')
{{-- HERO --}}
<section class="pb-hero">
    <div class="container">
        <div class="row align-items-center content">
            <div class="col-lg-6">
                <div class="badge-pill"><i class="fas fa-star text-warning"></i> Trusted by {{ $stats['patients'] }}+ patients</div>
                <h1>Your Smile Deserves <span style="color:#a5b4fc;">Expert Care</span></h1>
                <p class="lead">{{ \App\Models\Setting::get('clinic_name', config('app.name')) }} brings modern dentistry, experienced doctors, and a comfortable environment together — for the whole family.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('public.contact') }}" class="btn btn-light btn-lg fw-bold px-4"><i class="fas fa-calendar-check me-2"></i>Book an Appointment</a>
                    <a href="{{ route('public.services') }}" class="btn btn-outline-light btn-lg px-4">Our Services</a>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0">
                <div class="position-relative">
                    <img src="{{ asset('images/stock/hero-dentist-xray.jpg') }}" class="hero-img" alt="Dentist reviewing X-ray with patient">
                    <div class="stat-float">
                        <i class="fas fa-user-md"></i>
                        <div><strong>{{ $stats['dentists'] }}+</strong><span>Expert Dentists</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATS BAR --}}
<div class="pb-stats-bar">
    <div class="container">
        <div class="row text-center">
            <div class="col-6 col-md-3 stat"><h3>{{ $stats['patients'] }}+</h3><span>Happy Patients</span></div>
            <div class="col-6 col-md-3 stat"><h3>{{ $stats['dentists'] }}+</h3><span>Expert Dentists</span></div>
            <div class="col-6 col-md-3 stat"><h3>{{ $stats['services'] }}+</h3><span>Dental Services</span></div>
            <div class="col-6 col-md-3 stat"><h3>{{ $stats['years'] }}+</h3><span>Years of Experience</span></div>
        </div>
    </div>
</div>

{{-- WHY CHOOSE US --}}
<section class="pb-section">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width:640px;">
            <div class="pb-eyebrow">WHY CHOOSE US</div>
            <h2>Dental Care Built Around You</h2>
            <p class="sub mx-auto">We combine advanced technology with a gentle touch to make every visit comfortable and stress-free.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="pb-card">
                    <div class="icon"><i class="fas fa-user-md"></i></div>
                    <h5>Expert Dentists</h5>
                    <p>Highly trained specialists across every field of modern dentistry.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="pb-card">
                    <div class="icon"><i class="fas fa-calendar-check"></i></div>
                    <h5>Easy Scheduling</h5>
                    <p>Book online in seconds and get confirmed instantly — no phone tag.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="pb-card">
                    <div class="icon"><i class="fas fa-shield-alt"></i></div>
                    <h5>Safe &amp; Sterile</h5>
                    <p>Strict hygiene protocols and modern equipment for total peace of mind.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="pb-card">
                    <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
                    <h5>Transparent Pricing</h5>
                    <p>Clear, upfront pricing with flexible payment options — no surprises.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SERVICES PREVIEW --}}
<section class="pb-section bg-soft">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5 flex-wrap gap-3">
            <div>
                <div class="pb-eyebrow">OUR SERVICES</div>
                <h2 class="mb-0">Comprehensive Dental Treatments</h2>
            </div>
            <a href="{{ route('public.services') }}" class="btn btn-outline-primary">View All Services <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
        @php $pool = ['dental-room-1','dentist-xray-film','dental-chair-orange','clinic-reception','surgical-team-1','hero-dentist-xray']; @endphp
        <div class="row g-4">
            @forelse ($services as $i => $service)
                <div class="col-md-6 col-lg-4">
                    <div class="pb-service-card">
                        <img src="{{ asset('images/stock/' . $pool[$i % count($pool)] . '.jpg') }}" alt="{{ $service->name }}">
                        <div class="body">
                            <h5>{{ $service->name }}</h5>
                            <div class="meta mb-2">{{ $service->duration_minutes }} min @if($service->category) &middot; {{ ucfirst($service->category) }} @endif</div>
                            <div class="price">${{ number_format($service->price, 2) }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-4">Services will appear here once configured.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- TEAM PREVIEW --}}
@if ($dentists->isNotEmpty())
<section class="pb-section">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width:640px;">
            <div class="pb-eyebrow">MEET THE TEAM</div>
            <h2>Our Dental Specialists</h2>
            <p class="sub mx-auto">Experienced, caring professionals dedicated to your oral health.</p>
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

{{-- TESTIMONIALS --}}
<section class="pb-section bg-soft">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width:640px;">
            <div class="pb-eyebrow">TESTIMONIALS</div>
            <h2>What Our Patients Say</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="pb-testimonial">
                    <div class="stars">★★★★★</div>
                    <p>"Absolutely the best dental experience I've had. The staff were gentle, professional, and explained everything clearly."</p>
                    <div class="who">
                        <img src="{{ asset('images/stock/patient-smile-2.jpg') }}"><div><strong>Amina H.</strong><span>Patient</span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pb-testimonial">
                    <div class="stars">★★★★★</div>
                    <p>"Booking was so easy and I got a reminder before my visit. My kids actually enjoy coming here now!"</p>
                    <div class="who">
                        <img src="{{ asset('images/stock/patient-smile-1.jpg') }}"><div><strong>Yusuf A.</strong><span>Patient</span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pb-testimonial">
                    <div class="stars">★★★★★</div>
                    <p>"Transparent pricing and a modern clinic. I finally found a dental home for my whole family."</p>
                    <div class="who">
                        <img src="{{ asset('images/stock/patient-smile-3.jpg') }}"><div><strong>Fartun M.</strong><span>Patient</span></div>
                    </div>
                </div>
            </div>
            @foreach ($testimonials as $t)
                <div class="col-md-4">
                    <div class="pb-testimonial">
                        <div class="stars">{{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}</div>
                        <p>"{{ $t->message }}"</p>
                        <div class="who">
                            <div><strong>{{ $t->name }}</strong><span>Patient</span></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                @if (session('success'))
                    <div class="alert alert-success" style="border-radius:12px;">{{ session('success') }}</div>
                @endif
                <div class="text-center mb-4">
                    <h4>Share Your Experience</h4>
                    <p class="text-muted">Let others know how your visit went.</p>
                </div>
                <form method="POST" action="{{ route('public.testimonials.submit') }}" class="pb-form-floating">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rating</label>
                            <select name="rating" class="form-select">
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}">{{ str_repeat('★', $i) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Your Comment *</label>
                            <textarea name="message" class="form-control" rows="3" required>{{ old('message') }}</textarea>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary px-5"><i class="fas fa-paper-plane me-2"></i>Submit Comment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="pb-section">
    <div class="container">
        <div class="pb-cta text-center">
            <div class="content">
                <h2 class="mb-3">Ready for a Healthier Smile?</h2>
                <p class="mb-4" style="color:rgba(255,255,255,.85);">Schedule your visit today — our team is ready to welcome you.</p>
                <a href="{{ route('public.contact') }}" class="btn btn-light btn-lg fw-bold px-5"><i class="fas fa-calendar-check me-2"></i>Book Your Appointment</a>
            </div>
        </div>
    </div>
</section>
@endsection
