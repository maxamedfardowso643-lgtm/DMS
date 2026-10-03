@php
    $days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
@endphp
<div class="qv-header">
    <img src="{{ $dentist->user->photoUrl() }}" class="qv-avatar">
    <div>
        <h4 class="mb-0">{{ $dentist->user->name }}</h4>
        <div class="text-muted" style="font-size:.85rem;">
            {{ $dentist->dentist_code }} @if($dentist->specialization) &middot; {{ $dentist->specialization }} @endif
        </div>
        <span class="badge bg-{{ $dentist->is_active ? 'success' : 'secondary' }} mt-1">{{ $dentist->is_active ? 'Active' : 'Inactive' }}</span>
    </div>
</div>

<div class="qv-stats">
    <div class="qv-stat">
        <i class="fas fa-calendar-check"></i>
        <div><strong>{{ $dentist->appointments()->count() }}</strong><span>Appointments</span></div>
    </div>
    <div class="qv-stat">
        <i class="fas fa-calendar-day"></i>
        <div><strong>{{ $dentist->schedules->count() }}</strong><span>Working Days</span></div>
    </div>
    <div class="qv-stat">
        <i class="fas fa-id-badge"></i>
        <div><strong>{{ $dentist->license_number ?? '-' }}</strong><span>License #</span></div>
    </div>
</div>

<div class="qv-section">
    <h6><i class="fas fa-address-card"></i> Contact Information</h6>
    <div class="qv-grid">
        <div><span>Email</span><strong>{{ $dentist->user->email }}</strong></div>
        <div><span>Phone</span><strong>{{ $dentist->user->phone ?? '-' }}</strong></div>
    </div>
</div>

@if ($dentist->bio)
<div class="qv-section">
    <h6><i class="fas fa-info-circle"></i> Bio</h6>
    <p class="mb-0" style="font-size:.85rem;color:var(--text);">{{ $dentist->bio }}</p>
</div>
@endif

<div class="qv-section">
    <h6><i class="fas fa-calendar-alt"></i> Weekly Schedule</h6>
    @forelse ($dentist->schedules as $s)
        <div class="qv-row">
            <div><strong>{{ $days[$s->day_of_week] }}</strong></div>
            <span class="text-muted">{{ substr($s->start_time,0,5) }} - {{ substr($s->end_time,0,5) }}</span>
        </div>
    @empty
        <p class="text-muted mb-0" style="font-size:.85rem;">No weekly schedule set.</p>
    @endforelse
</div>

<div class="qv-section">
    <h6><i class="fas fa-calendar-check"></i> Recent Appointments</h6>
    @forelse ($dentist->appointments as $apt)
        <div class="qv-row">
            <div>
                <strong>{{ $apt->appointment_date->format('Y-m-d') }}</strong>
                <span class="text-muted">{{ $apt->patient->full_name ?? '-' }} — {{ $apt->service->name ?? '-' }}</span>
            </div>
            <span class="badge bg-{{ $apt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$apt->status)) }}</span>
        </div>
    @empty
        <p class="text-muted mb-0" style="font-size:.85rem;">No appointments yet.</p>
    @endforelse
</div>

<div class="qv-footer">
    <a href="{{ route('dentists.show', $dentist) }}" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt"></i> Open Full Profile</a>
</div>
