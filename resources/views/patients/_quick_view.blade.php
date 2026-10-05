@php
    $balance = $patient->invoices->sum('balance');
    $age = $patient->date_of_birth ? $patient->date_of_birth->age : null;
@endphp
<div class="qv-header">
    <img src="{{ $patient->photoUrl() }}"
         class="qv-avatar">
    <div>
        <h4 class="mb-0">{{ $patient->full_name }}</h4>
        <div class="text-muted" style="font-size:.85rem;">
            {{ $patient->patient_code }}
            @if ($age) &middot; {{ $age }} yrs @endif
            @if ($patient->gender) &middot; <span class="text-capitalize">{{ $patient->gender }}</span> @endif
        </div>
        <span class="badge bg-{{ $patient->is_active ? 'success' : 'secondary' }} mt-1">{{ $patient->is_active ? 'Active' : 'Inactive' }}</span>
    </div>
    <div class="ms-auto text-end">
        <div class="text-muted" style="font-size:.75rem;">Outstanding Balance</div>
        <div class="fw-bold {{ $balance > 0 ? 'text-danger' : 'text-success' }}" style="font-size:1.25rem;">${{ number_format($balance, 2) }}</div>
    </div>
</div>

<div class="qv-stats">
    <div class="qv-stat">
        <i class="fas fa-calendar-check"></i>
        <div><strong>{{ $patient->appointments()->count() }}</strong><span>Appointments</span></div>
    </div>
    <div class="qv-stat">
        <i class="fas fa-file-invoice-dollar"></i>
        <div><strong>{{ $patient->invoices->count() }}</strong><span>Invoices</span></div>
    </div>
    <div class="qv-stat">
        <i class="fas fa-tooth"></i>
        <div><strong>{{ $patient->dentalChartEntries->count() }}</strong><span>Chart Entries</span></div>
    </div>
</div>

<div class="qv-section">
    <h6><i class="fas fa-address-card"></i> Contact Information</h6>
    <div class="qv-grid">
        <div><span>Phone</span><strong>{{ $patient->phone ?? '-' }}</strong></div>
        <div><span>Email</span><strong>{{ $patient->email ?? '-' }}</strong></div>
        <div><span>Address</span><strong>{{ $patient->address ?? '-' }}</strong></div>
        <div><span>Emergency Contact</span><strong>{{ $patient->emergency_contact_name ?? '-' }} @if($patient->emergency_contact_phone) ({{ $patient->emergency_contact_phone }}) @endif</strong></div>
    </div>
</div>

@if ($patient->medical_history || $patient->allergies)
<div class="qv-section">
    <h6><i class="fas fa-notes-medical"></i> Medical Information</h6>
    <div class="qv-grid">
        <div><span>Medical History</span><strong>{{ $patient->medical_history ?? 'None recorded' }}</strong></div>
        <div><span>Allergies</span><strong class="{{ $patient->allergies ? 'text-danger' : '' }}">{{ $patient->allergies ?? 'None recorded' }}</strong></div>
    </div>
</div>
@endif

<div class="qv-section">
    <h6><i class="fas fa-calendar-check"></i> Recent Appointments</h6>
    @forelse ($patient->appointments as $apt)
        <div class="qv-row">
            <div>
                <strong>{{ $apt->appointment_date->format('Y-m-d') }}</strong>
                <span class="text-muted">{{ $apt->service->name ?? '-' }} with {{ $apt->dentist->user->name ?? '-' }}</span>
            </div>
            <span class="badge bg-{{ $apt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$apt->status)) }}</span>
        </div>
    @empty
        <p class="text-muted mb-0" style="font-size:.85rem;">No appointments yet.</p>
    @endforelse
</div>

<div class="qv-footer">
    <button type="button" class="btn btn-secondary btn-sm" onclick="$('#patientViewModal').modal('hide'); openLedger({{ $patient->id }});"><i class="fas fa-file-invoice"></i> Statement</button>
    <a href="{{ route('patients.show', $patient) }}" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt"></i> Open Full Profile</a>
</div>
