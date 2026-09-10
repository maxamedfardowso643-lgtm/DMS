@extends('public.layout')

@section('title', 'Gallery')

@section('content')
<div class="pb-page-header">
    <div class="container">
        <h1>Gallery</h1>
        <p>A look inside our clinic and the care we provide.</p>
    </div>
</div>

<section class="pb-section">
    <div class="container">
        <div class="row g-4 pb-gallery">
            @php
                $photos = [
                    ['file' => 'dental-room-1', 'label' => 'Modern Treatment Room'],
                    ['file' => 'hero-dentist-xray', 'label' => 'Reviewing Diagnostics'],
                    ['file' => 'dentist-xray-film', 'label' => 'Detailed Case Review'],
                    ['file' => 'dental-chair-orange', 'label' => 'Comfortable Treatment Chairs'],
                    ['file' => 'clinic-reception', 'label' => 'Reception & Waiting Area'],
                    ['file' => 'surgical-team-1', 'label' => 'Surgical Care Team'],
                    ['file' => 'surgical-team-2', 'label' => 'Our Dedicated Staff'],
                    ['file' => 'doctor-patient-talk', 'label' => 'Patient Consultations'],
                    ['file' => 'doctor-portrait', 'label' => 'Meet Our Specialists'],
                ];
            @endphp
            @foreach ($photos as $photo)
                <div class="col-md-4">
                    <a href="{{ asset('images/stock/' . $photo['file'] . '.jpg') }}" target="_blank" class="gitem position-relative">
                        <img src="{{ asset('images/stock/' . $photo['file'] . '.jpg') }}" alt="{{ $photo['label'] }}">
                        <span class="gallery-caption">{{ $photo['label'] }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
