@extends('layouts.app')

@section('title', 'Patient Registration Report')
@section('page-title', 'Patient Registration Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports &amp; Analytics</a></li>
    <li class="breadcrumb-item active">Patient Registration</li>
@endsection

@section('content')
@include('reports.partials.filter-bar', [
    'exportRoute' => 'reports.patients.registration.export',
    'exportPdfRoute' => 'reports.patients.registration.pdf',
    'showGender' => true,
    'showPatientType' => true,
])

<div class="card report-table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Registered Patients</h3>
        <span class="text-muted small">{{ $patients->total() }} result(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle report-table">
                <thead class="sticky-top">
                    <tr>
                        <th>Patient ID</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Registration Date</th>
                        <th>Type</th>
                        <th>Assigned Dentist</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($patients as $p)
                        <tr>
                            <td>{{ $p->patient_code }}</td>
                            <td><a href="{{ route('patients.show', $p) }}">{{ $p->full_name }}</a></td>
                            <td>{{ ucfirst($p->gender ?? '-') }}</td>
                            <td>{{ $p->date_of_birth?->age ?? '-' }}</td>
                            <td>{{ $p->phone ?: '-' }}</td>
                            <td>{{ $p->email ?: '-' }}</td>
                            <td>{{ $p->created_at->format('M j, Y') }}</td>
                            <td>
                                @if(($p->appointments_count ?? 0) > 0)
                                    <span class="badge text-bg-info">Returning</span>
                                @else
                                    <span class="badge text-bg-success">New</span>
                                @endif
                            </td>
                            <td>{{ $p->assigned_dentist_name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                No data found for the selected filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($patients->hasPages())
        <div class="card-footer">{{ $patients->links() }}</div>
    @endif
</div>
@endsection
