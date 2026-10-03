{{--
    Reusable report filter bar — a slim toolbar (active filters + actions) with
    all filter fields tucked inside a modal opened by the "Filter" button.

    Expected variables from the including view:
      $range            array from DateRangeResolver::resolve()
      $filters          array of the report's own filter values (keys vary per report)
      $exportRoute      route name for Excel export (optional)
      $exportPdfRoute   route name for PDF export (optional)
    Optional collections to switch on extra filter fields:
      $dentists, $services, $statuses, $paymentMethods, $categories
--}}
@php
    $presets = \App\Support\Reports\DateRangeResolver::PRESETS;
    $filters = $filters ?? [];
    $activeCount = collect($filters)->filter(fn ($v) => ! blank($v))->count();
@endphp

<div class="card report-filter-bar mb-3">
    <div class="card-body d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Reports
        </a>

        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reportFilterModal">
            <i class="fas fa-sliders-h me-1"></i> Filter
            @if($activeCount > 0)
                <span class="badge text-bg-light text-primary ms-1">{{ $activeCount }}</span>
            @endif
        </button>

        <span class="badge text-bg-light border">{{ \App\Support\Reports\DateRangeResolver::formatRange($range['from'], $range['to']) }}</span>
        @foreach ($filters as $key => $value)
            @continue(blank($value))
            <span class="badge text-bg-light border">{{ \Illuminate\Support\Str::headline($key) }}: {{ $value }}</span>
        @endforeach

        <div class="ms-auto d-flex flex-wrap gap-2">
            <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-undo me-1"></i> Reset</a>
            @isset($exportRoute)
                <a href="{{ route($exportRoute, request()->query()) }}" class="btn btn-success btn-sm"><i class="fas fa-file-excel me-1"></i> Export</a>
            @endisset
            @isset($exportPdfRoute)
                <a href="{{ route($exportPdfRoute, request()->query()) }}" target="_blank" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf me-1"></i> PDF</a>
            @endisset
            @isset($exportPdfRoute)
                <a href="{{ route($exportPdfRoute, request()->query()) }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="fas fa-print me-1"></i> Print</a>
            @else
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()"><i class="fas fa-print me-1"></i> Print</button>
            @endisset
        </div>
    </div>
</div>

<div class="modal fade" id="reportFilterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="GET">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-sliders-h me-1"></i> Filter Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label small text-muted mb-1">Date Range</label>
                        <select name="preset" class="form-select" id="datePreset">
                            @foreach ($presets as $key => $label)
                                <option value="{{ $key }}" @selected($range['preset'] === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 date-custom-field" @style(['display:none' => $range['preset'] !== 'custom'])>
                        <label class="form-label small text-muted mb-1">From</label>
                        <input type="date" name="from" value="{{ $range['from'] }}" class="form-control">
                    </div>
                    <div class="col-6 date-custom-field" @style(['display:none' => $range['preset'] !== 'custom'])>
                        <label class="form-label small text-muted mb-1">To</label>
                        <input type="date" name="to" value="{{ $range['to'] }}" class="form-control">
                    </div>

                    @isset($dentists)
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Dentist</label>
                            <select name="dentist_id" class="form-select">
                                <option value="">All Dentists</option>
                                @foreach ($dentists as $d)
                                    <option value="{{ $d->id }}" @selected(($filters['dentist_id'] ?? null) == $d->id)>{{ $d->user->name ?? 'Dr. #'.$d->id }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endisset

                    @isset($services)
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Treatment / Service</label>
                            <select name="service_id" class="form-select">
                                <option value="">All Services</option>
                                @foreach ($services as $s)
                                    <option value="{{ $s->id }}" @selected(($filters['service_id'] ?? null) == $s->id)>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endisset

                    @isset($categories)
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Category</label>
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c }}" @selected(($filters['category'] ?? null) == $c)>{{ ucfirst($c) }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endisset

                    @isset($statuses)
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $st)
                                    <option value="{{ $st }}" @selected(($filters['status'] ?? null) == $st)>{{ ucfirst(str_replace('_',' ', $st)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endisset

                    @isset($paymentMethods)
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Payment Method</label>
                            <select name="payment_method_id" class="form-select">
                                <option value="">All Methods</option>
                                @foreach ($paymentMethods as $m)
                                    <option value="{{ $m->id }}" @selected(($filters['payment_method_id'] ?? null) == $m->id)>{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endisset

                    @if(($showGender ?? false))
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Any</option>
                                <option value="male" @selected(($filters['gender'] ?? null) === 'male')>Male</option>
                                <option value="female" @selected(($filters['gender'] ?? null) === 'female')>Female</option>
                            </select>
                        </div>
                    @endif

                    @if(($showPatientType ?? false))
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Patient Type</label>
                            <select name="patient_type" class="form-select">
                                <option value="">All</option>
                                <option value="new" @selected(($filters['patient_type'] ?? null) === 'new')>New</option>
                                <option value="returning" @selected(($filters['patient_type'] ?? null) === 'returning')>Returning</option>
                            </select>
                        </div>
                    @endif

                    @if(($showSource ?? false))
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Type</label>
                            <select name="source" class="form-select">
                                <option value="">All</option>
                                <option value="scheduled" @selected(($filters['source'] ?? null) === 'scheduled')>Scheduled</option>
                                <option value="walk_in" @selected(($filters['source'] ?? null) === 'walk_in')>Walk-in</option>
                            </select>
                        </div>
                    @endif

                    @if(($showGroupBy ?? false))
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Group By</label>
                            <select name="group_by" class="form-select">
                                <option value="day" @selected(($groupBy ?? 'day') === 'day')>Day</option>
                                <option value="week" @selected(($groupBy ?? '') === 'week')>Week</option>
                                <option value="month" @selected(($groupBy ?? '') === 'month')>Month</option>
                                <option value="year" @selected(($groupBy ?? '') === 'year')>Year</option>
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary"><i class="fas fa-undo me-1"></i> Reset All</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i> Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>

@once
@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('#datePreset').forEach(function (select) {
        select.addEventListener('change', function () {
            const form = this.closest('form');
            const customFields = form.querySelectorAll('.date-custom-field');
            customFields.forEach(f => f.style.display = this.value === 'custom' ? '' : 'none');
        });
    });
});
</script>
@endpush
@endonce
