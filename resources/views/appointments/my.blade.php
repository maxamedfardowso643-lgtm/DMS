@extends('layouts.app')

@section('title', 'My Appointments')
@section('page-title', 'My Appointments')
@section('breadcrumb')
    <li class="breadcrumb-item active">My Appointments</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead><tr><th>No.</th><th>Date</th><th>Time</th><th>Dentist</th><th>Service</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($appointments as $apt)
                    <tr>
                        <td>{{ $apt->appointment_no }}</td>
                        <td>{{ $apt->appointment_date->format('Y-m-d') }}</td>
                        <td>{{ substr($apt->start_time,0,5) }}</td>
                        <td>{{ $apt->dentist->user->name ?? '-' }}</td>
                        <td>{{ $apt->service->name ?? '-' }}</td>
                        <td><span class="badge bg-{{ $apt->statusBadgeColor() }}">{{ ucfirst(str_replace('_',' ',$apt->status)) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">No appointments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
