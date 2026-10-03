@extends('layouts.app')

@section('title', 'Revenue Report')
@section('page-title', 'Revenue Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports &amp; Analytics</a></li>
    <li class="breadcrumb-item active">Revenue</li>
@endsection

@section('content')
@include('reports.partials.filter-bar', [
    'exportRoute' => 'reports.financial.revenue.export',
    'exportPdfRoute' => 'reports.financial.revenue.pdf',
    'showGroupBy' => true,
])

<div class="card report-table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Revenue Summary</h3>
        <button type="button" class="btn btn-sm btn-outline-primary drilldown-trigger" data-label="Total Revenue">
            <i class="fas fa-list me-1"></i> View Payment Details
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle report-table">
                <tbody>
                    <tr><th style="width:40%">Total Revenue</th><td>{{ number_format($summary['total_revenue'], 2) }}</td></tr>
                    <tr><th>Net Revenue</th><td>{{ number_format($summary['net_revenue'], 2) }}</td></tr>
                    <tr><th>Outstanding</th><td>{{ number_format($summary['outstanding'], 2) }}</td></tr>
                    <tr><th>Discounts</th><td>{{ number_format($summary['discounts'], 2) }}</td></tr>
                    <tr><th>Refunds</th><td>{{ number_format($summary['refunds'], 2) }}</td></tr>
                    <tr><th>Total Billed</th><td>{{ number_format($summary['billed'], 2) }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <div class="card report-table-card">
        <div class="card-header"><h3 class="card-title">Revenue Trend ({{ ucfirst($groupBy) }})</h3></div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 report-table">
                <thead><tr><th>Period</th><th>Revenue</th></tr></thead>
                <tbody>
                    @forelse ($trend as $period => $amount)
                        <tr><td>{{ $period }}</td><td>{{ number_format($amount, 2) }}</td></tr>
                    @empty
                        <tr><td colspan="2" class="text-center text-muted py-3">No data found for the selected filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<div class="row">
    <div class="col-md-6">
        <div class="card report-table-card">
            <div class="card-header"><h3 class="card-title">Revenue by Dentist</h3></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 report-table">
                    <thead><tr><th>Dentist</th><th>Revenue</th></tr></thead>
                    <tbody>
                        @forelse ($byDentist as $r)
                            <tr>
                                <td><a href="#" class="drilldown-trigger" data-dentist-id="{{ $r->dentist_id }}" data-label="{{ $r->dentist_name }}">{{ $r->dentist_name }}</a></td>
                                <td>{{ number_format($r->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-3">No data found for the selected filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card report-table-card">
            <div class="card-header"><h3 class="card-title">Revenue by Service</h3></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 report-table">
                    <thead><tr><th>Service</th><th>Revenue</th></tr></thead>
                    <tbody>
                        @forelse ($byService as $r)
                            <tr><td>{{ $r->name }}</td><td>{{ number_format($r->total, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-3">No data found for the selected filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card report-table-card">
            <div class="card-header"><h3 class="card-title">Revenue by Payment Method</h3></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 report-table">
                    <thead><tr><th>Method</th><th>Revenue</th></tr></thead>
                    <tbody>
                        @forelse ($byMethod as $r)
                            <tr><td>{{ ucfirst(str_replace('_',' ',$r->method)) }}</td><td>{{ number_format($r->total, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-3">No data found for the selected filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card report-table-card">
            <div class="card-header"><h3 class="card-title">Aging Receivables</h3></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 report-table">
                    <thead><tr><th>Bucket</th><th>Balance</th></tr></thead>
                    <tbody>
                        @forelse ($agingReceivables as $bucket => $amount)
                            <tr><td>{{ $bucket }}</td><td>{{ number_format($amount, 2) }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted py-3">No outstanding receivables.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Drill-down modal: shows the exact payment records behind whichever number was clicked --}}
<div class="modal fade" id="drilldownModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payments — <span id="drilldownLabel"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="drilldownLoading" class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-muted"></i></div>
                <table class="table table-sm table-hover d-none" id="drilldownTable">
                    <thead><tr><th>Payment No</th><th>Patient</th><th>Invoice</th><th>Amount</th><th>Method</th><th>Date</th></tr></thead>
                    <tbody></tbody>
                </table>
                <p class="text-center text-muted py-4 d-none" id="drilldownEmpty">No data found for the selected filters.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(function () {
    const modalEl = document.getElementById('drilldownModal');
    const modal = new bootstrap.Modal(modalEl);
    const drilldownUrl = @json(route('reports.financial.revenue.drilldown'));
    const baseParams = @json(request()->query());

    document.querySelectorAll('.drilldown-trigger').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('drilldownLabel').textContent = this.dataset.label || '';
            document.getElementById('drilldownLoading').classList.remove('d-none');
            document.getElementById('drilldownTable').classList.add('d-none');
            document.getElementById('drilldownEmpty').classList.add('d-none');
            modal.show();

            const params = new URLSearchParams(baseParams);
            if (this.dataset.dentistId) params.set('dentist_id', this.dataset.dentistId);

            fetch(drilldownUrl + '?' + params.toString())
                .then(r => r.json())
                .then(data => {
                    document.getElementById('drilldownLoading').classList.add('d-none');
                    const tbody = document.querySelector('#drilldownTable tbody');
                    tbody.innerHTML = '';
                    if (!data.rows.length) {
                        document.getElementById('drilldownEmpty').classList.remove('d-none');
                        return;
                    }
                    data.rows.forEach(row => {
                        tbody.insertAdjacentHTML('beforeend', `<tr>
                            <td>${row.payment_no}</td>
                            <td>${row.patient}</td>
                            <td>${row.invoice_no}</td>
                            <td>${Number(row.amount).toFixed(2)}</td>
                            <td>${row.method}</td>
                            <td>${row.date ?? '-'}</td>
                        </tr>`);
                    });
                    document.getElementById('drilldownTable').classList.remove('d-none');
                });
        });
    });
});
</script>
@endpush
