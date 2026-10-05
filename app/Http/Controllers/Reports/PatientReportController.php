<?php

namespace App\Http\Controllers\Reports;

use App\Exports\Reports\PatientRegistrationExport;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Support\Reports\DateRangeResolver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PatientReportController extends Controller
{
    public function registration(Request $request): View
    {
        $range = DateRangeResolver::resolve($request);
        $filters = $this->filters($request);

        $query = $this->baseQuery($range, $filters);

        $patients = (clone $query)
            ->orderByDesc('patients.created_at')
            ->paginate(25)
            ->withQueryString();

        $dentists = \App\Models\Dentist::with('user')->whereHas('user')->where('is_active', true)->get();

        return view('reports.patients.registration', [
            'range' => $range,
            'filters' => $filters,
            'patients' => $patients,
            'dentists' => $dentists,
        ]);
    }

    public function registrationExport(Request $request)
    {
        $range = DateRangeResolver::resolve($request);
        $filters = $this->filters($request);

        return \Maatwebsite\Excel\Facades\Excel::download(
            new PatientRegistrationExport($this->baseQuery($range, $filters)),
            "patient-registration-{$range['from']}-to-{$range['to']}.xlsx"
        );
    }

    public function registrationPdf(Request $request)
    {
        $range = DateRangeResolver::resolve($request);
        $filters = $this->filters($request);

        $patients = $this->baseQuery($range, $filters)->orderByDesc('patients.created_at')->limit(1000)->get();

        if (! class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return response('PDF export requires the barryvdh/laravel-dompdf package to be installed.', 503);
        }

        $pdf = app('dompdf.wrapper')->loadView('reports.pdf.patient-registration', [
            'range' => $range,
            'filters' => $filters,
            'patients' => $patients,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("patient-registration-{$range['from']}-to-{$range['to']}.pdf");
    }

    protected function filters(Request $request): array
    {
        return [
            'gender' => $request->get('gender'),
            'dentist_id' => $request->get('dentist_id'),
            'patient_type' => $request->get('patient_type'), // new | returning
        ];
    }

    protected function baseQuery(array $range, array $filters): Builder
    {
        $latestAppointment = DB::table('appointments')
            ->select('patient_id', DB::raw('MAX(id) as latest_id'))
            ->groupBy('patient_id');

        $query = Patient::query()
            ->select('patients.*')
            ->withCount('appointments')
            ->selectSub(
                DB::table('appointments as la')
                    ->joinSub($latestAppointment, 'lam', fn ($j) => $j->on('la.id', '=', 'lam.latest_id'))
                    ->join('dentists', 'la.dentist_id', '=', 'dentists.id')
                    ->join('users', 'dentists.user_id', '=', 'users.id')
                    ->whereColumn('la.patient_id', 'patients.id')
                    ->select('users.name')
                    ->limit(1),
                'assigned_dentist_name'
            )
            ->whereBetween('patients.created_at', ["{$range['from']} 00:00:00", "{$range['to']} 23:59:59"]);

        if (! empty($filters['gender'])) {
            $query->where('patients.gender', $filters['gender']);
        }

        if (! empty($filters['dentist_id'])) {
            $query->whereHas('appointments', fn ($q) => $q->where('dentist_id', $filters['dentist_id']));
        }

        if ($filters['patient_type'] === 'new') {
            $query->doesntHave('appointments');
        } elseif ($filters['patient_type'] === 'returning') {
            $query->has('appointments');
        }

        return $query;
    }
}
