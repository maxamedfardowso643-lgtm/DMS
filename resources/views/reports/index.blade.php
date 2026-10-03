@extends('layouts.app')

@section('title', 'Reports & Analytics')
@section('page-title', 'Reports & Analytics')
@section('breadcrumb')
    <li class="breadcrumb-item active">Reports & Analytics</li>
@endsection

@section('content')
@php
    $user = auth()->user();

    $reports = collect([
        ['category' => 'Patients', 'icon' => 'fa-user-injured', 'title' => 'Patient Registration Report', 'desc' => 'New patient sign-ups with contact details and assigned dentist.', 'route' => 'reports.patients.registration', 'roles' => ['admin', 'receptionist', 'dentist']],
        ['category' => 'Appointments', 'icon' => 'fa-calendar-check', 'title' => 'Appointment Summary Report', 'desc' => 'Every appointment with status, dentist, service, date and time.', 'route' => 'reports.appointments.summary', 'roles' => ['admin', 'receptionist', 'dentist']],
        ['category' => 'Treatments', 'icon' => 'fa-tooth', 'title' => 'Treatment Summary Report', 'desc' => 'Treatments delivered per type/category with revenue generated.', 'route' => 'reports.treatments.summary', 'roles' => ['admin', 'accountant', 'dentist']],
        ['category' => 'Financial', 'icon' => 'fa-dollar-sign', 'title' => 'Revenue Report', 'desc' => 'Revenue, refunds, discounts, aging receivables and payment methods.', 'route' => 'reports.financial.revenue', 'roles' => ['admin', 'accountant']],
    ])->filter(fn ($r) => $user->hasAnyRole($r['roles']))->groupBy('category');
@endphp

<div class="card">
    <div class="card-body">
        @forelse ($reports as $category => $items)
            <h6 class="text-muted text-uppercase small fw-bold mt-3 mb-2">{{ $category }}</h6>
            <div class="list-group mb-2">
                @foreach ($items as $r)
                    <a href="{{ route($r['route']) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                        <i class="fas {{ $r['icon'] }} text-primary" style="width:20px;text-align:center;"></i>
                        <span>
                            <span class="d-block fw-semibold">{{ $r['title'] }}</span>
                            <span class="d-block text-muted small">{{ $r['desc'] }}</span>
                        </span>
                        <i class="fas fa-chevron-right ms-auto text-muted"></i>
                    </a>
                @endforeach
            </div>
        @empty
            <p class="text-muted text-center py-4 mb-0">No reports available for your role.</p>
        @endforelse
    </div>
</div>
@endsection
