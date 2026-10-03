@extends('public.layout')

@section('title', 'Our Team')

@section('content')
<div class="pb-page-header">
    <div class="container">
        <h1>Our Team</h1>
        <p>Experienced dental professionals dedicated to your care.</p>
    </div>
</div>

<section class="pb-section">
    <div class="container">
        <div class="row g-4">
            @forelse ($dentists as $dentist)
                <div class="col-md-6 col-lg-3">
                    <div class="pb-team-card">
                        <img src="{{ $dentist->user->photoUrl(400) }}" alt="{{ $dentist->user->name }}">
                        <div class="body">
                            <h5>{{ $dentist->user->name }}</h5>
                            <div class="role mb-2">{{ $dentist->specialization ?? 'General Dentist' }}</div>
                            @if ($dentist->bio)
                                <p class="text-muted" style="font-size:.82rem;">{{ \Illuminate\Support\Str::limit($dentist->bio, 90) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">Team information is being updated.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
