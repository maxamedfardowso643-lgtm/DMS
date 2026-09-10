<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        $appointmentsPerDay = Appointment::selectRaw('appointment_date, COUNT(*) as total')
            ->whereBetween('appointment_date', [$from, $to])
            ->groupBy('appointment_date')->orderBy('appointment_date')->get();

        $totalAppointments = Appointment::whereBetween('appointment_date', [$from, $to])->count();
        $cancelled = Appointment::whereBetween('appointment_date', [$from, $to])->where('status', 'cancelled')->count();
        $noShow = Appointment::whereBetween('appointment_date', [$from, $to])->where('status', 'no_show')->count();

        $revenueByDentist = Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->join('appointments', 'invoices.appointment_id', '=', 'appointments.id')
            ->join('dentists', 'appointments.dentist_id', '=', 'dentists.id')
            ->join('users', 'dentists.user_id', '=', 'users.id')
            ->selectRaw('users.name as dentist_name, SUM(payments.amount) as total')
            ->whereBetween('payments.payment_date', [$from, $to])
            ->where('payments.type', 'payment')
            ->groupBy('dentists.id', 'users.name')
            ->orderByDesc('total')
            ->get();

        $revenueByService = Payment::join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->join('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->join('services', 'invoice_items.service_id', '=', 'services.id')
            ->selectRaw('services.name, SUM(invoice_items.line_total) as total')
            ->whereBetween('payments.payment_date', [$from, $to])
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('total')
            ->get();

        $topServices = Service::withCount(['appointments' => function ($q) use ($from, $to) {
            $q->whereBetween('appointment_date', [$from, $to]);
        }])->orderByDesc('appointments_count')->take(5)->get();

        $patientGrowth = Patient::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('date')->orderBy('date')->get();

        $dailyCashCollection = Payment::where('type', 'payment')
            ->whereBetween('payment_date', [$from, $to])
            ->selectRaw('payment_date, SUM(amount) as total')
            ->groupBy('payment_date')->orderBy('payment_date')->get();

        $agingReceivables = \App\Models\Invoice::whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
            ->get()
            ->groupBy(fn ($inv) => match (true) {
                now()->diffInDays($inv->due_date ?? $inv->issue_date, false) > 0 => 'Not yet due',
                now()->diffInDays($inv->due_date ?? $inv->issue_date) <= 30 => '1-30 days',
                now()->diffInDays($inv->due_date ?? $inv->issue_date) <= 60 => '31-60 days',
                default => '60+ days',
            })
            ->map(fn ($group) => $group->sum('balance'));

        return view('reports.index', compact(
            'from', 'to', 'appointmentsPerDay', 'totalAppointments', 'cancelled', 'noShow',
            'revenueByService', 'revenueByDentist', 'topServices', 'patientGrowth', 'dailyCashCollection', 'agingReceivables'
        ));
    }

    public function export(Request $request)
    {
        $from = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AppointmentsExport($from, $to),
            "appointments_{$from}_to_{$to}.xlsx"
        );
    }
}
