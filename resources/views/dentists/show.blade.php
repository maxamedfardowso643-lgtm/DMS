@extends('layouts.app')

@section('title', $dentist->user->name)
@section('page-title', 'Dentist Profile')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dentists.index') }}">Dentists</a></li>
    <li class="breadcrumb-item active">{{ $dentist->user->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile text-center">
                <h3 class="profile-username">{{ $dentist->user->name }}</h3>
                <p class="text-muted">{{ $dentist->dentist_code }} — {{ $dentist->specialization }}</p>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item"><b>Email</b> <span class="float-right">{{ $dentist->user->email }}</span></li>
                    <li class="list-group-item"><b>Phone</b> <span class="float-right">{{ $dentist->user->phone ?? '-' }}</span></li>
                    <li class="list-group-item"><b>License</b> <span class="float-right">{{ $dentist->license_number ?? '-' }}</span></li>
                </ul>
                <p>{{ $dentist->bio }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3 class="card-title">Weekly Schedule</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        @php $days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']; @endphp
                        @foreach ($dentist->schedules->where('type','weekly')->sortBy('day_of_week') as $s)
                            <tr><td>{{ $days[$s->day_of_week] }}</td><td>{{ substr($s->start_time,0,5) }} - {{ substr($s->end_time,0,5) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Recent Appointments</h3></div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Date</th><th>Patient</th><th>Service</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($dentist->appointments->sortByDesc('appointment_date')->take(20) as $apt)
                            <tr>
                                <td>{{ $apt->appointment_date->format('Y-m-d') }}</td>
                                <td>{{ $apt->patient->full_name ?? '-' }}</td>
                                <td>{{ $apt->service->name ?? '-' }}</td>
                                <td><span class="badge bg-{{ $apt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$apt->status)) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">No appointments yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
