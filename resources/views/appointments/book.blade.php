@extends('layouts.app')

@section('title', 'Book Appointment')
@section('page-title', 'Book Appointment')
@section('breadcrumb')
    <li class="breadcrumb-item active">Book Appointment</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Book an Appointment</h3></div>
            <div class="card-body">
                <form id="booking-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dentist *</label>
                            <select name="dentist_id" id="dentist_select" class="form-control" required>
                                <option value="">Select dentist</option>
                                @foreach ($dentists as $d)
                                    <option value="{{ $d->id }}">{{ $d->user->name }} ({{ $d->specialization }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Service *</label>
                            <select name="service_id" id="service_select" class="form-control" required>
                                <option value="">Select service</option>
                                @foreach ($services as $s)
                                    <option value="{{ $s->id }}" data-duration="{{ $s->duration_minutes }}" data-price="{{ $s->price }}">{{ $s->name }} ({{ $s->duration_minutes }} min)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date *</label>
                            <input type="date" name="appointment_date" id="appointment_date" class="form-control" required min="{{ now(config('app.clinic_timezone'))->toDateString() }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Available Time Slots *</label>
                            <select name="start_time" id="slots_select" class="form-control" required>
                                <option value="">Select dentist, service &amp; date first</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Anything the dentist should know (optional)"></textarea>
                        </div>
                    </div>

                    <div class="alert alert-info d-flex justify-content-between align-items-center" id="fee-preview" style="display:none!important;">
                        <span>Estimated Fee</span>
                        <strong id="fee-amount">0.00</strong>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-calendar-check me-1"></i> Confirm Booking</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
function updateFeePreview() {
    const opt = $('#service_select option:selected');
    const price = parseFloat(opt.data('price'));
    if (opt.val() && !isNaN(price)) {
        $('#fee-amount').text(price.toFixed(2));
        $('#fee-preview').css('display', 'flex');
    } else {
        $('#fee-preview').css('display', 'none');
    }
}

function fetchSlots() {
    const dentist_id = $('#dentist_select').val();
    const service_id = $('#service_select').val();
    const date = $('#appointment_date').val();
    if (!dentist_id || !service_id || !date) return;

    $.get('{{ route('book-appointment.available-slots') }}', { dentist_id, service_id, date }, function (res) {
        const select = $('#slots_select');
        select.empty();
        if (res.slots.length === 0) {
            select.append(`<option value="">${res.message || 'No slots available'}</option>`);
        } else {
            select.append('<option value="">Select a time</option>');
            res.slots.forEach(s => select.append(`<option value="${s}">${s}</option>`));
        }
    });
}

$('#service_select').on('change', updateFeePreview);
$('#dentist_select, #service_select, #appointment_date').on('change', fetchSlots);

$('#booking-form').on('submit', function (e) {
    e.preventDefault();

    $.ajax({
        url: '{{ route('book-appointment.store') }}',
        method: 'POST',
        data: $(this).serialize(),
        success: function (res) {
            toastr.success(res.message);
            setTimeout(() => { window.location.href = res.redirect || '{{ route('my-appointments') }}'; }, 1200);
        },
        error: function (xhr) {
            if (xhr.status === 422 && xhr.responseJSON.errors) {
                showFormErrors($('#booking-form'), xhr.responseJSON.errors);
            } else {
                toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
            }
        }
    });
});
</script>
@endpush
