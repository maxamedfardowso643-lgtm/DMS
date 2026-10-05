@extends('layouts.app')

@section('title', $patient->full_name)
@section('page-title', 'Patient Profile')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Patients</a></li>
    <li class="breadcrumb-item active">{{ $patient->full_name }}</li>
@endsection

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('patients.ledger', $patient) }}" target="_blank" class="btn btn-secondary btn-sm">
        <i class="fas fa-file-invoice"></i> Print Statement / Ledger
    </a>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile text-center">
                <img class="profile-user-img img-fluid img-circle" style="width:100px;height:100px;object-fit:cover;"
                     src="{{ $patient->photoUrl() }}">
                <h3 class="profile-username text-center mt-2">{{ $patient->full_name }}</h3>
                <p class="text-muted text-center">{{ $patient->patient_code }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Phone</b> <span class="float-right">{{ $patient->phone ?? '-' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Email</b> <span class="float-right">{{ $patient->email ?? '-' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Gender</b> <span class="float-right text-capitalize">{{ $patient->gender ?? '-' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>DOB</b> <span class="float-right">{{ $patient->date_of_birth?->format('Y-m-d') ?? '-' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Allergies</b> <span class="float-right">{{ $patient->allergies ?? 'None' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card card-secondary card-outline">
            <div class="card-header"><h3 class="card-title">Dental Chart</h3></div>
            <div class="card-body">
                <div class="tooth-chart" id="dental-chart">
                    @php
                        $upper = ['18','17','16','15','14','13','12','11','21','22','23','24','25','26','27','28'];
                        $lower = ['48','47','46','45','44','43','42','41','31','32','33','34','35','36','37','38'];
                        $entries = $patient->dentalChartEntries->keyBy('tooth_number');
                    @endphp
                    @foreach ($upper as $tooth)
                        @php $cond = $entries[$tooth]->condition ?? 'healthy'; @endphp
                        <div class="tooth {{ $cond }}" data-tooth="{{ $tooth }}" data-current="{{ $cond }}" title="Tooth {{ $tooth }}" onclick="cycleTooth(this)">{{ $tooth }}</div>
                    @endforeach
                    <div class="w-100"></div>
                    @foreach ($lower as $tooth)
                        @php $cond = $entries[$tooth]->condition ?? 'healthy'; @endphp
                        <div class="tooth {{ $cond }}" data-tooth="{{ $tooth }}" data-current="{{ $cond }}" title="Tooth {{ $tooth }}" onclick="cycleTooth(this)">{{ $tooth }}</div>
                    @endforeach
                </div>
                <small class="text-muted d-block mt-2">Riix ilig si aad u beddesho xaaladdiisa (healthy → decayed → filled → missing → crowned → root canal → implant → extracted → impacted).</small>
                <button type="button" class="btn btn-primary btn-sm mt-2" onclick="saveDentalChart({{ $patient->id }})">
                    <i class="fas fa-save"></i> Save Dental Chart
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#appointments" data-bs-toggle="tab">Appointments</a></li>
                    <li class="nav-item"><a class="nav-link" href="#invoices" data-bs-toggle="tab">Invoices</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="appointments">
                        <table class="table table-sm table-striped">
                            <thead><tr><th>No.</th><th>Date</th><th>Dentist</th><th>Service</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse ($patient->appointments as $apt)
                                    <tr>
                                        <td>{{ $apt->appointment_no }}</td>
                                        <td>{{ $apt->appointment_date->format('Y-m-d') }} {{ $apt->start_time }}</td>
                                        <td>{{ $apt->dentist->user->name ?? '-' }}</td>
                                        <td>{{ $apt->service->name ?? '-' }}</td>
                                        <td><span class="badge bg-{{ $apt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$apt->status)) }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">No appointments yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="invoices">
                        <table class="table table-sm table-striped">
                            <thead><tr><th>Invoice #</th><th>Date</th><th>Total</th><th>Paid</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse ($patient->invoices as $inv)
                                    <tr>
                                        <td><a href="{{ route('invoices.show', $inv) }}">{{ $inv->invoice_no }}</a></td>
                                        <td>{{ $inv->issue_date->format('Y-m-d') }}</td>
                                        <td>{{ number_format($inv->total_amount, 2) }}</td>
                                        <td>{{ number_format($inv->paid_amount, 2) }}</td>
                                        <td><span class="badge bg-{{ $inv->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$inv->status)) }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">No invoices yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
const dentalConditions = ['healthy','decayed','filled','missing','crowned','root_canal','implant','extracted','impacted'];

function cycleTooth(el) {
    const current = dentalConditions.indexOf($(el).data('current') || 'healthy');
    const next = dentalConditions[(current + 1) % dentalConditions.length];
    $(el).removeClass(dentalConditions.join(' ')).addClass(next).data('current', next);
}

function saveDentalChart(patientId) {
    const toothConditions = {};
    $('#dental-chart .tooth').each(function () {
        toothConditions[$(this).data('tooth')] = $(this).data('current') || 'healthy';
    });

    $.ajax({
        url: `/patients/${patientId}/dental-chart`,
        method: 'POST',
        data: { tooth_conditions: toothConditions },
        success: function (res) {
            toastr.success(res.message);
        },
        error: function (xhr) {
            toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
        }
    });
}
</script>
@endpush
