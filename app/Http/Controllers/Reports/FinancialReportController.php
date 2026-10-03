<?php

namespace App\Http\Controllers\Reports;

use App\Exports\Reports\RevenueExport;
use App\Http\Controllers\Controller;
use App\Models\Dentist;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use App\Models\Service;
use App\Support\Reports\DateRangeResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FinancialReportController extends Controller
{
    public function revenue(Request $request): View
    {
        $range = DateRangeResolver::resolve($request);
        $filters = $this->filters($request);
        $groupBy = in_array($request->get('group_by'), ['day', 'week', 'month', 'year']) ? $request->get('group_by') : 'day';

        $paymentsQuery = $this->paymentsQuery($range, $filters);

        $totalRevenue = (clone $paymentsQuery)->where('payments.type', 'payment')->sum('payments.amount');
        $totalRefunds = (clone $paymentsQuery)->where('payments.type', 'refund')->sum('payments.amount');
        $totalDiscounts = (clone $paymentsQuery)->where('payments.type', 'payment')->sum('payments.discount_amount');

        $invoiceStats = Invoice::whereBetween('issue_date', [$range['from'], $range['to']])
            ->when(! empty($filters['dentist_id']), fn ($q) => $q->whereHas('appointment', fn ($a) => $a->where('dentist_id', $filters['dentist_id'])))
            ->selectRaw("SUM(total_amount) as billed, SUM(paid_amount) as paid, SUM(total_amount - paid_amount) as outstanding")
            ->first();

        $summary = [
            'total_revenue' => (float) $totalRevenue,
            'refunds' => (float) $totalRefunds,
            'discounts' => (float) $totalDiscounts,
            'net_revenue' => (float) $totalRevenue - (float) $totalRefunds,
            'outstanding' => (float) ($invoiceStats->outstanding ?? 0),
            'billed' => (float) ($invoiceStats->billed ?? 0),
        ];

        $dateFormat = match ($groupBy) {
            'week' => '%x-W%v',
            'month' => '%Y-%m',
            'year' => '%Y',
            default => '%Y-%m-%d',
        };

        $trend = (clone $paymentsQuery)
            ->where('payments.type', 'payment')
            ->selectRaw("DATE_FORMAT(payments.payment_date, '{$dateFormat}') as bucket, SUM(payments.amount) as total")
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->pluck('total', 'bucket');

        $byDentist = (clone $paymentsQuery)
            ->where('payments.type', 'payment')
            ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->join('appointments', 'invoices.appointment_id', '=', 'appointments.id')
            ->join('dentists', 'appointments.dentist_id', '=', 'dentists.id')
            ->join('users', 'dentists.user_id', '=', 'users.id')
            ->selectRaw('dentists.id as dentist_id, users.name as dentist_name, SUM(payments.amount) as total')
            ->groupBy('dentists.id', 'users.name')
            ->orderByDesc('total')
            ->get();

        $byService = (clone $paymentsQuery)
            ->where('payments.type', 'payment')
            ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->join('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->join('services', 'invoice_items.service_id', '=', 'services.id')
            ->selectRaw('services.id as service_id, services.name, SUM(invoice_items.line_total) as total')
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('total')
            ->get();

        $byMethod = (clone $paymentsQuery)
            ->where('payments.type', 'payment')
            ->selectRaw('payments.method, SUM(payments.amount) as total')
            ->groupBy('payments.method')
            ->orderByDesc('total')
            ->get();

        $agingReceivables = Invoice::whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
            ->get()
            ->groupBy(fn ($inv) => match (true) {
                now()->diffInDays($inv->due_date ?? $inv->issue_date, false) > 0 => 'Not yet due',
                now()->diffInDays($inv->due_date ?? $inv->issue_date) <= 30 => '0-30 Days',
                now()->diffInDays($inv->due_date ?? $inv->issue_date) <= 60 => '31-60 Days',
                now()->diffInDays($inv->due_date ?? $inv->issue_date) <= 90 => '61-90 Days',
                default => '90+ Days',
            })
            ->map(fn ($group) => $group->sum('balance'));

        return view('reports.financial.revenue', [
            'range' => $range,
            'filters' => $filters,
            'groupBy' => $groupBy,
            'summary' => $summary,
            'trend' => $trend,
            'byDentist' => $byDentist,
            'byService' => $byService,
            'byMethod' => $byMethod,
            'agingReceivables' => $agingReceivables,
            'dentists' => Dentist::with('user')->where('is_active', true)->get(),
            'services' => Service::where('is_active', true)->orderBy('name')->get(),
            'paymentMethods' => PaymentMethod::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    /**
     * Drill-down: returns the exact payment records behind a summary number
     * (e.g. Total Revenue, or one dentist's/service's slice of it).
     */
    public function revenueDrilldown(Request $request): JsonResponse
    {
        $range = DateRangeResolver::resolve($request);
        $filters = $this->filters($request);

        $query = $this->paymentsQuery($range, $filters)
            ->where('payments.type', 'payment')
            ->with(['invoice', 'patient']);

        if ($request->filled('dentist_id')) {
            $query->whereHas('invoice.appointment', fn ($q) => $q->where('dentist_id', $request->get('dentist_id')));
        }

        $payments = $query->orderByDesc('payments.payment_date')->limit(500)->get();

        return response()->json([
            'count' => $payments->count(),
            'total' => $payments->sum('amount'),
            'rows' => $payments->map(fn ($p) => [
                'payment_no' => $p->payment_no,
                'patient' => $p->patient->full_name ?? '-',
                'invoice_no' => $p->invoice->invoice_no ?? '-',
                'amount' => (float) $p->amount,
                'method' => $p->method,
                'date' => optional($p->payment_date)->format('Y-m-d'),
            ]),
        ]);
    }

    public function revenueExport(Request $request)
    {
        $range = DateRangeResolver::resolve($request);
        $filters = $this->filters($request);

        $payments = $this->paymentsQuery($range, $filters)
            ->where('payments.type', 'payment')
            ->with(['invoice', 'patient', 'receivedBy'])
            ->orderByDesc('payments.payment_date')
            ->get();

        return \Maatwebsite\Excel\Facades\Excel::download(
            new RevenueExport($payments),
            "revenue-{$range['from']}-to-{$range['to']}.xlsx"
        );
    }

    public function revenuePdf(Request $request)
    {
        $range = DateRangeResolver::resolve($request);
        $filters = $this->filters($request);
        $groupBy = 'day';

        // Re-derive the same summary the screen shows, kept lightweight for print.
        $paymentsQuery = $this->paymentsQuery($range, $filters);
        $totalRevenue = (clone $paymentsQuery)->where('payments.type', 'payment')->sum('payments.amount');
        $totalRefunds = (clone $paymentsQuery)->where('payments.type', 'refund')->sum('payments.amount');

        $byDentist = (clone $paymentsQuery)
            ->where('payments.type', 'payment')
            ->join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->join('appointments', 'invoices.appointment_id', '=', 'appointments.id')
            ->join('dentists', 'appointments.dentist_id', '=', 'dentists.id')
            ->join('users', 'dentists.user_id', '=', 'users.id')
            ->selectRaw('users.name as dentist_name, SUM(payments.amount) as total')
            ->groupBy('users.name')
            ->orderByDesc('total')
            ->get();

        if (! class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return response('PDF export requires the barryvdh/laravel-dompdf package to be installed.', 503);
        }

        $pdf = app('dompdf.wrapper')->loadView('reports.pdf.revenue', [
            'range' => $range,
            'filters' => $filters,
            'totalRevenue' => $totalRevenue,
            'totalRefunds' => $totalRefunds,
            'netRevenue' => $totalRevenue - $totalRefunds,
            'byDentist' => $byDentist,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("revenue-{$range['from']}-to-{$range['to']}.pdf");
    }

    protected function filters(Request $request): array
    {
        return [
            'dentist_id' => $request->get('dentist_id'),
            'service_id' => $request->get('service_id'),
            'payment_method_id' => $request->get('payment_method_id'),
        ];
    }

    protected function paymentsQuery(array $range, array $filters)
    {
        $query = \App\Models\Payment::query()
            ->whereBetween('payments.payment_date', [$range['from'], $range['to']]);

        if (! empty($filters['payment_method_id'])) {
            $query->where('payments.payment_method_id', $filters['payment_method_id']);
        }

        if (! empty($filters['dentist_id']) || ! empty($filters['service_id'])) {
            $query->whereHas('invoice.appointment', function ($q) use ($filters) {
                if (! empty($filters['dentist_id'])) {
                    $q->where('dentist_id', $filters['dentist_id']);
                }
                if (! empty($filters['service_id'])) {
                    $q->where('service_id', $filters['service_id']);
                }
            });
        }

        return $query;
    }
}
