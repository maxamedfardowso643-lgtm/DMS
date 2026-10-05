<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Reports\AppointmentReportController;
use App\Http\Controllers\Reports\FinancialReportController;
use App\Http\Controllers\Reports\PatientReportController;
use App\Http\Controllers\Reports\TreatmentReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StoredFileController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Uploaded images (restored from the database if the disk lost them)
Route::get('/storage/{path}', [StoredFileController::class, 'show'])->where('path', '.*')->name('stored-file');

// Public marketing website
Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/about', [PublicController::class, 'about'])->name('public.about');
Route::get('/our-services', [PublicController::class, 'services'])->name('public.services');
Route::get('/our-team', [PublicController::class, 'team'])->name('public.team');
Route::get('/gallery', [PublicController::class, 'gallery'])->name('public.gallery');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');
Route::post('/contact', [PublicController::class, 'submitContact'])->name('public.contact.submit');
Route::post('/appointment-request', [PublicController::class, 'submitBookingRequest'])->name('public.book-appointment');
Route::post('/testimonials', [PublicController::class, 'submitTestimonial'])->name('public.testimonials.submit');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])->name('register.submit');

    Route::get('password/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('notifications/count', [\App\Http\Controllers\NotificationController::class, 'count'])->name('notifications.count');
    Route::get('notifications/{id}/open', [\App\Http\Controllers\NotificationController::class, 'open'])->name('notifications.open');
    Route::post('notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'readAll'])->name('notifications.read-all');

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
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::resource('roles', RoleController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('schedules', ScheduleController::class);
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::resource('payment-methods', \App\Http\Controllers\PaymentMethodController::class)->except(['show']);

        Route::get('leads', [\App\Http\Controllers\Admin\LeadController::class, 'index'])->name('leads.index');
        Route::post('leads/requests/{appointmentRequest}/status', [\App\Http\Controllers\Admin\LeadController::class, 'updateStatus'])->name('leads.requests.status');
        Route::post('leads/requests/{appointmentRequest}/convert', [\App\Http\Controllers\Admin\LeadController::class, 'convert'])->name('leads.requests.convert');
        Route::delete('leads/requests/{appointmentRequest}', [\App\Http\Controllers\Admin\LeadController::class, 'destroyRequest'])->name('leads.requests.destroy');
        Route::post('leads/messages/{contactMessage}/read', [\App\Http\Controllers\Admin\LeadController::class, 'markMessageRead'])->name('leads.messages.read');
        Route::delete('leads/messages/{contactMessage}', [\App\Http\Controllers\Admin\LeadController::class, 'destroyMessage'])->name('leads.messages.destroy');
        Route::post('leads/testimonials/{testimonial}/approve', [\App\Http\Controllers\Admin\LeadController::class, 'approveTestimonial'])->name('leads.testimonials.approve');
        Route::post('leads/testimonials/{testimonial}/unapprove', [\App\Http\Controllers\Admin\LeadController::class, 'unapproveTestimonial'])->name('leads.testimonials.unapprove');
        Route::post('leads/testimonials/{testimonial}/add-as-lead', [\App\Http\Controllers\Admin\LeadController::class, 'addTestimonialAsLead'])->name('leads.testimonials.add-as-lead');
        Route::delete('leads/testimonials/{testimonial}', [\App\Http\Controllers\Admin\LeadController::class, 'destroyTestimonial'])->name('leads.testimonials.destroy');
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
    });

    // Reporting Center: hub is open to every role that can see at least one report;
    // individual report routes are further restricted to match REPORT PERMISSIONS below.
    Route::middleware('role:admin,accountant,dentist,receptionist')->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');

        Route::middleware('role:admin,receptionist,dentist')->prefix('patients')->name('patients.')->group(function () {
            Route::get('/registration', [PatientReportController::class, 'registration'])->name('registration');
            Route::get('/registration/export', [PatientReportController::class, 'registrationExport'])->name('registration.export');
            Route::get('/registration/pdf', [PatientReportController::class, 'registrationPdf'])->name('registration.pdf');
        });

        Route::middleware('role:admin,receptionist,dentist')->prefix('appointments')->name('appointments.')->group(function () {
            Route::get('/summary', [AppointmentReportController::class, 'summary'])->name('summary');
            Route::get('/summary/export', [AppointmentReportController::class, 'summaryExport'])->name('summary.export');
            Route::get('/summary/pdf', [AppointmentReportController::class, 'summaryPdf'])->name('summary.pdf');
        });

        Route::middleware('role:admin,accountant,dentist')->prefix('treatments')->name('treatments.')->group(function () {
            Route::get('/summary', [TreatmentReportController::class, 'summary'])->name('summary');
            Route::get('/summary/export', [TreatmentReportController::class, 'summaryExport'])->name('summary.export');
            Route::get('/summary/pdf', [TreatmentReportController::class, 'summaryPdf'])->name('summary.pdf');
        });

        Route::middleware('role:admin,accountant')->prefix('financial')->name('financial.')->group(function () {
            Route::get('/revenue', [FinancialReportController::class, 'revenue'])->name('revenue');
            Route::get('/revenue/export', [FinancialReportController::class, 'revenueExport'])->name('revenue.export');
            Route::get('/revenue/pdf', [FinancialReportController::class, 'revenuePdf'])->name('revenue.pdf');
            Route::get('/revenue/drilldown', [FinancialReportController::class, 'revenueDrilldown'])->name('revenue.drilldown');
        });
    });

    Route::middleware('role:patient')->group(function () {
        Route::get('my-appointments', [AppointmentController::class, 'myAppointments'])->name('my-appointments');
        Route::get('my-invoices', [InvoiceController::class, 'myInvoices'])->name('my-invoices');
        Route::get('book-appointment', [AppointmentController::class, 'bookingForm'])->name('book-appointment');
        Route::post('book-appointment', [AppointmentController::class, 'storeMyBooking'])->name('book-appointment.store');
        Route::get('book-appointment/available-slots', [AppointmentController::class, 'availableSlots'])->name('book-appointment.available-slots');
        Route::post('my-appointments/{appointment}/cancel', [AppointmentController::class, 'cancelMyBooking'])->name('my-appointments.cancel');
    });
});
