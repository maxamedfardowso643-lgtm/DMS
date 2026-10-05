<?php

namespace App\Http\Controllers\Reports;

use App\Exports\Reports\TreatmentSummaryExport;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Service;
use App\Support\Reports\DateRangeResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TreatmentReportController extends Controller
{
    public function summary(Request $request): View
    {
        $range = DateRangeResolver::resolve($request);
        $filters = $this->filters($request);

        $rows = $this->rows($range, $filters);

        $summary = [
            'total_treatments' => $rows->sum('total'),
            'total_revenue' => $rows->sum('revenue'),
            'completed' => $rows->sum('completed'),
            'categories' => $rows->pluck('category')->unique()->filter()->count(),
        ];

        return view('reports.treatments.summary', [
            'range' => $range,
            'filters' => $filters,
            'rows' => $rows,
            'summary' => $summary,
            'dentists' => Dentist::with('user')->whereHas('user')->where('is_active', true)->get(),
            'services' => Service::where('is_active', true)->orderBy('name')->get(),
            'categories' => Service::whereNotNull('category')->distinct()->orderBy('category')->pluck('category'),
        ]);
    }

    public function summaryExport(Request $request)
    {
        $range = DateRangeResolver::resolve($request);
        $filters = $this->filters($request);

        return \Maatwebsite\Excel\Facades\Excel::download(
            new TreatmentSummaryExport($this->rows($range, $filters)),
            "treatment-summary-{$range['from']}-to-{$range['to']}.xlsx"
        );
    }

    public function summaryPdf(Request $request)
    {
        $range = DateRangeResolver::resolve($request);
        $filters = $this->filters($request);
        $rows = $this->rows($range, $filters);

        if (! class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return response('PDF export requires the barryvdh/laravel-dompdf package to be installed.', 503);
        }

        $pdf = app('dompdf.wrapper')->loadView('reports.pdf.treatment-summary', [
            'range' => $range,
            'filters' => $filters,
            'rows' => $rows,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("treatment-summary-{$range['from']}-to-{$range['to']}.pdf");
    }

    protected function filters(Request $request): array
    {
        return [
            'dentist_id' => $request->get('dentist_id'),
            'service_id' => $request->get('service_id'),
            'category' => $request->get('category'),
            'status' => $request->get('status'),
        ];
    }

    /**
     * One row per service ("treatment type") with counts + revenue, driven
     * entirely by real appointment/invoice/payment records for the range.
     */
    protected function rows(array $range, array $filters)
    {
        $appointmentQuery = Appointment::query()
            ->whereBetween('appointment_date', [$range['from'], $range['to']]);

        if (! empty($filters['dentist_id'])) {
            $appointmentQuery->where('dentist_id', $filters['dentist_id']);
        }
        if (! empty($filters['service_id'])) {
            $appointmentQuery->where('service_id', $filters['service_id']);
        }
        if (! empty($filters['status'])) {
            $appointmentQuery->where('status', $filters['status']);
        }

        $counts = (clone $appointmentQuery)
            ->selectRaw('service_id, COUNT(*) as total, COUNT(DISTINCT patient_id) as patients')
            ->selectRaw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed")
            ->selectRaw("SUM(CASE WHEN status IN ('booked','confirmed','checked_in','in_progress') THEN 1 ELSE 0 END) as pending")
            ->selectRaw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled")
            ->groupBy('service_id')
            ->get()
            ->keyBy('service_id');

        $revenue = DB::table('payments')
            ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->join('appointments', 'invoices.appointment_id', '=', 'appointments.id')
            ->whereBetween('appointments.appointment_date', [$range['from'], $range['to']])
            ->where('payments.type', 'payment')
            ->when(! empty($filters['dentist_id']), fn ($q) => $q->where('appointments.dentist_id', $filters['dentist_id']))
            ->when(! empty($filters['service_id']), fn ($q) => $q->where('appointments.service_id', $filters['service_id']))
            ->when(! empty($filters['status']), fn ($q) => $q->where('appointments.status', $filters['status']))
            ->selectRaw('appointments.service_id, SUM(payments.amount) as revenue')
            ->groupBy('appointments.service_id')
            ->pluck('revenue', 'service_id');

        $services = Service::query()
            ->when(! empty($filters['category']), fn ($q) => $q->where('category', $filters['category']))
            ->when(! empty($filters['service_id']), fn ($q) => $q->where('id', $filters['service_id']))
            ->get();

        return $services->map(function (Service $service) use ($counts, $revenue) {
            $c = $counts->get($service->id);

            return (object) [
                'id' => $service->id,
                'name' => $service->name,
                'category' => $service->category,
                'total' => $c->total ?? 0,
                'patients' => $c->patients ?? 0,
                'completed' => $c->completed ?? 0,
                'pending' => $c->pending ?? 0,
                'cancelled' => $c->cancelled ?? 0,
                'revenue' => (float) ($revenue[$service->id] ?? 0),
            ];
        })
            ->filter(fn ($row) => $row->total > 0)
            ->sortByDesc('total')
            ->values();
    }
}
