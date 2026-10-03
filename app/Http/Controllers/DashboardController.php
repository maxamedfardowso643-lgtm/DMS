<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\InventoryItem;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user->hasRole('patient')) {
            return $this->patientDashboard();
        }

        $today = today();

        $stats = [
            'todays_appointments' => Appointment::whereDate('appointment_date', $today)->count(),
            'total_patients' => Patient::count(),
            'pending_invoices' => Invoice::whereIn('status', ['unpaid', 'partially_paid', 'overdue'])->count(),
            'revenue_this_month' => Payment::where('type', 'payment')
                ->whereMonth('payment_date', $today->month)
                ->whereYear('payment_date', $today->year)
                ->sum('amount'),
        ];

        $todaysAppointments = Appointment::with(['patient', 'dentist.user', 'service'])
            ->whereDate('appointment_date', $today)
            ->orderBy('start_time')
            ->get();

        $lowStockItems = InventoryItem::whereColumn('quantity_on_hand', '<=', 'reorder_level')
            ->where('is_active', true)
            ->get();

        $appointmentsPerDay = Appointment::selectRaw('appointment_date, COUNT(*) as total')
            ->whereBetween('appointment_date', [$today->copy()->subDays(6), $today])
            ->groupBy('appointment_date')
            ->orderBy('appointment_date')
            ->get();

        // Detail lists shown when a stat card is clicked
        $recentPatients = Patient::latest()->take(50)->get();

        $pendingInvoices = Invoice::with('patient')
            ->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
            ->orderBy('due_date')
            ->get();

        $monthPayments = Payment::with(['patient', 'paymentMethod'])
            ->where('type', 'payment')
            ->whereMonth('payment_date', $today->month)
            ->whereYear('payment_date', $today->year)
            ->latest('payment_date')
            ->get();

        $collected = (float) Payment::where('type', 'payment')->sum('amount');
        $refunds = (float) Payment::where('type', 'refund')->sum('amount');

        $netBalance = [
            'billed' => (float) Invoice::whereNotIn('status', ['draft', 'cancelled'])->sum('total_amount'),
            'collected' => $collected,
            'refunds' => $refunds,
            'net' => $collected - $refunds,
            'outstanding' => (float) Invoice::whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
                ->selectRaw('COALESCE(SUM(GREATEST(total_amount - paid_amount, 0)), 0) as due')
                ->value('due'),
        ];

        return view('dashboard.index', compact('stats', 'todaysAppointments', 'lowStockItems', 'appointmentsPerDay', 'netBalance', 'recentPatients', 'pendingInvoices', 'monthPayments'));
    }

    protected function patientDashboard(): View
    {
        $patient = Auth::user()->patient;

        $upcomingAppointments = $patient
            ? $patient->appointments()->with(['dentist.user', 'service'])
                ->whereDate('appointment_date', '>=', today())
                ->orderBy('appointment_date')
                ->get()
            : collect();

        $invoices = $patient
            ? $patient->invoices()->latest()->take(5)->get()
            : collect();

        $outstandingBalance = $patient
            ? $patient->invoices()->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])->get()->sum(fn ($i) => (float) $i->balance)
            : 0;

        return view('dashboard.patient', compact('patient', 'upcomingAppointments', 'invoices', 'outstandingBalance'));
    }
}
