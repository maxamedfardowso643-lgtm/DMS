<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public marketing website
Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/about', [PublicController::class, 'about'])->name('public.about');
Route::get('/our-services', [PublicController::class, 'services'])->name('public.services');
Route::get('/our-team', [PublicController::class, 'team'])->name('public.team');
Route::get('/gallery', [PublicController::class, 'gallery'])->name('public.gallery');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');
Route::post('/contact', [PublicController::class, 'submitContact'])->name('public.contact.submit');
Route::post('/book-appointment', [PublicController::class, 'submitBookingRequest'])->name('public.book-appointment');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Admin / Receptionist / Accountant / Dentist manageable modules
    Route::middleware('role:admin,receptionist')->group(function () {
        Route::resource('patients', PatientController::class);
    });

    Route::middleware('role:admin,receptionist,dentist,accountant')->group(function () {
        Route::get('patients-search', [PatientController::class, 'search'])->name('patients.search');
        Route::get('patients/{patient}/ledger', [PatientController::class, 'ledger'])->name('patients.ledger');
        Route::get('patients/{patient}/ledger-modal', [PatientController::class, 'ledgerModal'])->name('patients.ledger-modal');
        Route::get('patients/{patient}/quick-view', [PatientController::class, 'quickView'])->name('patients.quick-view');
        Route::post('patients/{patient}/dental-chart', [PatientController::class, 'updateDentalChart'])->name('patients.dental-chart.update');
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('dentists', DentistController::class);
        Route::get('dentists/{dentist}/quick-view', [DentistController::class, 'quickView'])->name('dentists.quick-view');
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('schedules', ScheduleController::class);
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::resource('payment-methods', \App\Http\Controllers\PaymentMethodController::class)->except(['show']);

        Route::get('leads', [\App\Http\Controllers\Admin\LeadController::class, 'index'])->name('leads.index');
        Route::post('leads/requests/{appointmentRequest}/status', [\App\Http\Controllers\Admin\LeadController::class, 'updateStatus'])->name('leads.requests.status');
        Route::delete('leads/requests/{appointmentRequest}', [\App\Http\Controllers\Admin\LeadController::class, 'destroyRequest'])->name('leads.requests.destroy');
        Route::post('leads/messages/{contactMessage}/read', [\App\Http\Controllers\Admin\LeadController::class, 'markMessageRead'])->name('leads.messages.read');
        Route::delete('leads/messages/{contactMessage}', [\App\Http\Controllers\Admin\LeadController::class, 'destroyMessage'])->name('leads.messages.destroy');
    });

    Route::middleware('role:admin,receptionist,dentist')->group(function () {
        Route::resource('appointments', AppointmentController::class);
        Route::post('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
        Route::post('appointments/{appointment}/reschedule', [AppointmentController::class, 'reschedule'])->name('appointments.reschedule');
        Route::get('appointments-calendar/events', [AppointmentController::class, 'calendarEvents'])->name('appointments.calendar-events');
        Route::get('appointments-available-slots', [AppointmentController::class, 'availableSlots'])->name('appointments.available-slots');
    });

    Route::middleware('role:admin,dentist')->group(function () {
        Route::get('treatments-patient-appointments', [TreatmentController::class, 'patientAppointments'])->name('treatments.patient-appointments');
        Route::resource('treatments', TreatmentController::class);
        Route::post('treatments/{treatment}/prescriptions', [TreatmentController::class, 'storePrescription'])->name('treatments.prescriptions.store');
    });

    Route::middleware('role:admin,receptionist,accountant')->group(function () {
        Route::resource('invoices', InvoiceController::class);
        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
        Route::get('appointments/{appointment}/services', [InvoiceController::class, 'appointmentServices'])->name('appointments.services');
        Route::resource('payments', PaymentController::class)->except(['show']);
        Route::get('payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
        Route::get('patients/{patient}/outstanding', [PaymentController::class, 'patientOutstanding'])->name('patients.outstanding');
    });

    Route::middleware('role:admin,accountant')->group(function () {
        Route::resource('inventory', InventoryController::class);
        Route::post('inventory/{inventory}/stock-movement', [InventoryController::class, 'stockMovement'])->name('inventory.stock-movement');
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    });

    Route::middleware('role:patient')->group(function () {
        Route::get('my-appointments', [AppointmentController::class, 'myAppointments'])->name('my-appointments');
        Route::get('my-invoices', [InvoiceController::class, 'myInvoices'])->name('my-invoices');
    });
});
