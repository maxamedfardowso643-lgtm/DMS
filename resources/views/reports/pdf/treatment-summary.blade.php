@extends('reports.pdf.layout')

@section('report-title', 'Treatment Summary Report')

@section('report-content')
<table class="data">
    <thead>
        <tr><th>Treatment</th><th>Category</th><th>Total</th><th>Patients</th><th>Completed</th><th>Pending</th><th>Cancelled</th><th>Revenue</th></tr>
    </thead>
    <tbody>
        @forelse ($rows as $r)
            <tr>
                <td>{{ $r->name }}</td>
                <td>{{ $r->category ? ucfirst($r->category) : '-' }}</td>
                <td>{{ $r->total }}</td>
                <td>{{ $r->patients }}</td>
                <td>{{ $r->completed }}</td>
                <td>{{ $r->pending }}</td>
                <td>{{ $r->cancelled }}</td>
                <td class="text-end">{{ number_format($r->revenue, 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="8">No data found for the selected filters.</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="7" class="text-end">Total Revenue</th>
            <th class="text-end">{{ number_format($rows->sum('revenue'), 2) }}</th>
        </tr>
    </tfoot>
</table>
@endsection
