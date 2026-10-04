@extends('layouts.app')

@section('title', 'Treatment Record')
@section('page-title', 'Treatment Record')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('treatments.index') }}">Treatments</a></li>
    <li class="breadcrumb-item active">#{{ $treatment->id }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Visit Info</h3></div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th>Patient</th><td>{{ $treatment->patient->full_name ?? '-' }}</td></tr>
                    <tr><th>Dentist</th><td>{{ $treatment->dentist->user->name ?? '-' }}</td></tr>
                    <tr><th>Visit Date</th><td>{{ $treatment->visit_date->format('Y-m-d') }}</td></tr>
                    <tr><th>Diagnosis</th><td>{{ $treatment->diagnosis ?? '-' }}</td></tr>
                    <tr><th>Notes</th><td>{{ $treatment->notes ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3 class="card-title">Procedures</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Service</th><th>Tooth</th><th>Qty</th><th>Price</th></tr></thead>
                    <tbody>
                        @foreach ($treatment->details as $d)
                            <tr>
                                <td>{{ $d->service->name ?? '-' }}</td>
                                <td>{{ $d->tooth_number ?? '-' }}</td>
                                <td>{{ $d->quantity }}</td>
                                <td>{{ number_format($d->unit_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Prescriptions</h3>
                <button class="btn btn-primary btn-xs" onclick="$('#rxModal').modal('show')"><i class="fas fa-plus"></i> Add</button>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Medicine</th><th>Dosage</th><th>Frequency</th><th>Duration</th></tr></thead>
                    <tbody id="rx-body">
                        @forelse ($treatment->prescriptions as $rx)
                            <tr>
                                <td>{{ $rx->medicine_name }}</td>
                                <td>{{ $rx->dosage ?? '-' }}</td>
                                <td>{{ $rx->frequency ?? '-' }}</td>
                                <td>{{ $rx->duration ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">No prescriptions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rxModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rx-form">
                <div class="modal-header">
                    <h5 class="modal-title">Add Prescription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Medicine *</label><input type="text" name="medicine_name" class="form-control" required></div>
                    <div class="row">
                        <div class="col-4 mb-3"><label class="form-label">Dosage</label><input type="text" name="dosage" class="form-control"></div>
                        <div class="col-4 mb-3"><label class="form-label">Frequency</label><input type="text" name="frequency" class="form-control"></div>
                        <div class="col-4 mb-3"><label class="form-label">Duration</label><input type="text" name="duration" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Instructions</label><textarea name="instructions" class="form-control" rows="2"></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$('#rx-form').on('submit', function (e) {
    e.preventDefault();
    $.post('{{ route('treatments.prescriptions.store', $treatment) }}', $(this).serialize())
        .done(res => { toastr.success(res.message); location.reload(); })
        .fail(() => toastr.error('Something went wrong.'));
});
</script>
@endpush
