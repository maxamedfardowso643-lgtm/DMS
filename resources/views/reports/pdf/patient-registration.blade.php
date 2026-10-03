@extends('reports.pdf.layout')

@section('report-title', 'Patient Registration Report')

@section('report-content')
<table class="data">
    <thead>
        <tr>
            <th>Patient ID</th><th>Full Name</th><th>Gender</th><th>Age</th>
            <th>Phone</th><th>Email</th><th>Registration Date</th><th>Assigned Dentist</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($patients as $p)
            <tr>
                <td>{{ $p->patient_code }}</td>
                <td>{{ $p->full_name }}</td>
                <td>{{ ucfirst($p->gender ?? '-') }}</td>
                <td>{{ $p->date_of_birth?->age ?? '-' }}</td>
                <td>{{ $p->phone ?: '-' }}</td>
                <td>{{ $p->email ?: '-' }}</td>
                <td>{{ $p->created_at->format('Y-m-d') }}</td>
                <td>{{ $p->assigned_dentist_name ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="8">No data found for the selected filters.</td></tr>
        @endforelse
    </tbody>
</table>
<p><strong>Total:</strong> {{ $patients->count() }} patient(s)</p>
@endsection
