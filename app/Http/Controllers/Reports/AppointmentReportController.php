<?php

namespace App\Http\Controllers\Reports;

use App\Exports\Reports\AppointmentSummaryExport;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Service;
use App\Support\Reports\DateRangeResolver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentReportController extends Controller
{
    public const STATUSES = ['booked', 'confirmed', 'checked_in', 'in_progress', 'completed', 'cancelled', 'no_show'];

    public function summary(Request $request): View
    {
        $range = DateRangeResolver::resolve($request);
        $filters = $this->filters($request);
        $query = $this->baseQuery($range, $filters);

        $appointments = (clone $query)
            ->with(['patient', 'dentist.user', 'service'])
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->paginate(25)
            ->withQueryString();

        return view('reports.appointments.summary', [
            'range' => $range,
            'filters' => $filters,
            'appointments' => $appointments,
            'dentists' => Dentist::with('user')->where('is_active', true)->get(),
            'services' => Service::where('is_active', true)->orderBy('name')->get(),
            'statuses' => self::STATUSES,
        ]);
    }

    public function summaryExport(Request $request)
    {
        $range = DateRangeResolver::resolve($request);
        $filters = $this->filters($request);

        return \Maatwebsite\Excel\Facades\Excel::download(
            new AppointmentSummaryExport($this->baseQuery($range, $filters)->with(['patient', 'dentist.user', 'service'])),
            "appointment-summary-{$range['from']}-to-{$range['to']}.xlsx"
        );
    }

    public function summaryPdf(Request $request)
    {
        $range = DateRangeResolver::resolve($request);
        $filters = $this->filters($request);

        $appointments = $this->baseQuery($range, $filters)
            ->with(['patient', 'dentist.user', 'service'])
            ->orderByDesc('appointment_date')
            ->limit(1000)
            ->get();

        if (! class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return response('PDF export requires the barryvdh/laravel-dompdf package to be installed.', 503);
        }

        $pdf = app('dompdf.wrapper')->loadView('reports.pdf.appointment-summary', [
            'range' => $range,
            'filters' => $filters,
            'appointments' => $appointments,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("appointment-summary-{$range['from']}-to-{$range['to']}.pdf");
    }

    protected function filters(Request $request): array
    {
        return [
            'dentist_id' => $request->get('dentist_id'),
            'patient_id' => $request->get('patient_id'),
            'service_id' => $request->get('service_id'),
            'status' => $request->get('status'),
            'source' => $request->get('source'),
        ];
    }

    protected function baseQuery(array $range, array $filters): Builder
    {
        $query = Appointment::query()
            ->whereBetween('appointment_date', [$range['from'], $range['to']]);

        if (! empty($filters['dentist_id'])) {
            $query->where('dentist_id', $filters['dentist_id']);
        }
        if (! empty($filters['patient_id'])) {
            $query->where('patient_id', $filters['patient_id']);
        }
        if (! empty($filters['service_id'])) {
            $query->where('service_id', $filters['service_id']);
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['source'])) {
            $query->where('source', $filters['source']);
        }

        return $query;
    }
}
