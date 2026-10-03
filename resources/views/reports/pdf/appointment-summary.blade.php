@extends('reports.pdf.layout')

@section('report-title', 'Appointment Summary Report')

@section('report-content')
<table class="data">
    <thead>
        <tr><th>Appointment No</th><th>Patient</th><th>Dentist</th><th>Service</th><th>Date</th><th>Time</th><th>Status</th></tr>
    </thead>
    <tbody>
        @forelse ($appointments as $a)
            <tr>
                <td>{{ $a->appointment_no }}</td>
                <td>{{ $a->patient->full_name ?? '-' }}</td>
                <td>{{ $a->dentist->user->name ?? '-' }}</td>
                <td>{{ $a->service->name ?? '-' }}</td>
                <td>{{ $a->appointment_date->format('Y-m-d') }}</td>
                <td>{{ substr($a->start_time, 0, 5) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $a->status)) }}</td>
            </tr>
        @empty
            <tr><td colspan="7">No data found for the selected filters.</td></tr>
        @endforelse
    </tbody>
</table>
<p><strong>Total:</strong> {{ $appointments->count() }} appointment(s)</p>
@endsection
