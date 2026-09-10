@extends('public.layout')

@section('title', 'Contact Us')

@section('content')
<div class="pb-page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <p>Book an appointment or send us a message — we'd love to hear from you.</p>
    </div>
</div>

<section class="pb-section">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="pb-contact-info-card">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Address</h6>
                        <p class="text-muted mb-0" style="font-size:.85rem;">{{ \App\Models\Setting::get('clinic_address', 'Not set') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pb-contact-info-card">
                    <i class="fas fa-phone-alt"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Phone</h6>
                        <p class="text-muted mb-0" style="font-size:.85rem;">{{ \App\Models\Setting::get('clinic_phone', 'Not set') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="pb-contact-info-card">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Working Hours</h6>
                        <p class="text-muted mb-0" style="font-size:.85rem;">{{ \App\Models\Setting::get('working_hours', 'Not set') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5">
            {{-- Book Appointment --}}
            <div class="col-lg-6">
                <div class="pb-eyebrow">QUICK BOOKING</div>
                <h2 class="mb-4">Request an Appointment</h2>

                <form id="booking-form" class="pb-form-floating">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number *</label>
                            <input type="text" name="phone" class="form-control" required placeholder="252 6X XXXXXXX">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Service</label>
                            <select name="service_id" class="form-select">
                                <option value="">Not sure yet</option>
                                @foreach ($services as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Preferred Date</label>
                            <input type="date" name="preferred_date" class="form-control" min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Preferred Time</label>
                            <input type="time" name="preferred_time" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Tell us more about what you need..."></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg w-100"><i class="fas fa-calendar-check me-2"></i>Request Appointment</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Contact Message --}}
            <div class="col-lg-6">
                <div class="pb-eyebrow">GENERAL INQUIRY</div>
                <h2 class="mb-4">Send Us a Message</h2>

                @if (session('success'))
                    <div class="alert alert-success" style="border-radius:12px;">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('public.contact.submit') }}" class="pb-form-floating">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="{{ old('subject') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message *</label>
                            <textarea name="message" class="form-control" rows="4" required>{{ old('message') }}</textarea>
                        </div>
                        @if ($errors->any())
                            <div class="col-12">
                                <div class="alert alert-danger py-2" style="border-radius:10px;font-size:.85rem;">{{ $errors->first() }}</div>
                            </div>
                        @endif
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-primary btn-lg w-100">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script>
$('#booking-form').on('submit', function (e) {
    e.preventDefault();
    const $btn = $(this).find('button[type="submit"]');
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');

    $.ajax({
        url: '{{ route('public.book-appointment') }}', method: 'POST', data: $(this).serialize(),
        success: (res) => {
            Swal.fire({ icon: 'success', title: 'Request Sent!', text: res.message, confirmButtonColor: '#4f46e5' });
            $('#booking-form')[0].reset();
        },
        error: (xhr) => {
            const msg = xhr.responseJSON?.errors ? Object.values(xhr.responseJSON.errors).flat()[0] : 'Something went wrong. Please try again.';
            Swal.fire({ icon: 'error', title: 'Oops', text: msg });
        },
        complete: () => $btn.prop('disabled', false).html('<i class="fas fa-calendar-check me-2"></i>Request Appointment'),
    });
});
</script>
@endpush
