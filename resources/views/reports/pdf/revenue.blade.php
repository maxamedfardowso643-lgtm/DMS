@extends('reports.pdf.layout')

@section('report-title', 'Revenue Report')

@section('report-content')
<table class="kpis">
    <tr>
        <td><span class="value">{{ number_format($totalRevenue, 2) }}</span><span class="label">TOTAL REVENUE</span></td>
        <td><span class="value">{{ number_format($totalRefunds, 2) }}</span><span class="label">REFUNDS</span></td>
        <td><span class="value">{{ number_format($netRevenue, 2) }}</span><span class="label">NET REVENUE</span></td>
    </tr>
</table>

<p class="section-title">Revenue by Dentist</p>
<table class="data">
    <thead><tr><th>Dentist</th><th>Revenue</th></tr></thead>
    <tbody>
        @forelse ($byDentist as $r)
            <tr><td>{{ $r->dentist_name }}</td><td class="text-end">{{ number_format($r->total, 2) }}</td></tr>
        @empty
            <tr><td colspan="2">No data found for the selected filters.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
