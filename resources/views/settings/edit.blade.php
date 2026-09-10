@extends('layouts.app')

@section('title', 'Settings')
@section('page-title', 'Clinic Settings')
@section('breadcrumb')
    <li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Clinic Configuration</h3></div>
    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="d-flex align-items-center gap-3 mb-4 p-3" style="background:var(--surface-2);border-radius:var(--radius);">
                <img id="logo-preview"
                     src="{{ $settings['clinic_logo'] ? asset('storage/' . $settings['clinic_logo']) : 'https://ui-avatars.com/api/?name=' . urlencode($settings['clinic_name'] ?? 'Clinic') }}"
                     style="width:64px;height:64px;border-radius:14px;object-fit:cover;border:1px solid var(--border);">
                <div>
                    <label class="form-label mb-1">Clinic Logo</label>
                    <input type="file" name="logo" accept="image/*" class="form-control form-control-sm" onchange="previewLogo(this)">
                    <small class="text-muted">Shown in the sidebar and on printed receipts.</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">Clinic Name *</label><input type="text" name="clinic_name" class="form-control" value="{{ $settings['clinic_name'] }}" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">Phone</label><input type="text" name="clinic_phone" class="form-control" value="{{ $settings['clinic_phone'] }}"></div>
                <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="clinic_email" class="form-control" value="{{ $settings['clinic_email'] }}"></div>
                <div class="col-md-6 mb-3"><label class="form-label">Working Hours</label><input type="text" name="working_hours" class="form-control" value="{{ $settings['working_hours'] }}"></div>
                <div class="col-md-12 mb-3"><label class="form-label">Address</label><textarea name="clinic_address" class="form-control" rows="2">{{ $settings['clinic_address'] }}</textarea></div>
                <div class="col-md-3 mb-3"><label class="form-label">Currency *</label><input type="text" name="currency" class="form-control" value="{{ $settings['currency'] }}" required></div>
                <div class="col-md-3 mb-3"><label class="form-label">Currency Symbol *</label><input type="text" name="currency_symbol" class="form-control" value="{{ $settings['currency_symbol'] }}" required></div>
                <div class="col-md-3 mb-3"><label class="form-label">Tax Rate (%) *</label><input type="number" step="0.01" name="tax_rate" class="form-control" value="{{ $settings['tax_rate'] }}" required></div>
                <div class="col-md-3 mb-3"><label class="form-label">Invoice Prefix *</label><input type="text" name="invoice_prefix" class="form-control" value="{{ $settings['invoice_prefix'] }}" required></div>
                <div class="col-md-3 mb-3"><label class="form-label">Appointment Prefix *</label><input type="text" name="appointment_prefix" class="form-control" value="{{ $settings['appointment_prefix'] }}" required></div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Settings</button>
            <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary"><i class="fas fa-wallet"></i> Manage Payment Methods</a>
        </div>
    </form>
</div>
@endsection

@push('js')
<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        $('#logo-preview').attr('src', URL.createObjectURL(input.files[0]));
    }
}
</script>
@endpush
