@php
    $user = auth()->user();
    $clinicName = \App\Models\Setting::get('clinic_name', config('app.name'));
    $clinicLogo = \App\Models\Setting::get('clinic_logo');
@endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        @if ($clinicLogo)
            <img src="{{ asset('storage/' . $clinicLogo) }}" alt="Logo" style="width:32px;height:32px;border-radius:8px;object-fit:cover;">
        @else
            <i class="fas fa-tooth ml-2 mr-2"></i>
        @endif
        <span class="brand-text font-weight-light">{{ \Illuminate\Support\Str::limit($clinicName, 18) }}</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                <li class="nav-header">MAIN</li>
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @if ($user->hasAnyRole(['admin', 'receptionist', 'dentist', 'accountant']))
                <li class="nav-header">CLINICAL</li>
                @endif

                @if ($user->hasAnyRole(['admin', 'receptionist', 'dentist']))
                <li class="nav-item">
                    <a href="{{ route('appointments.index') }}" class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Appointments</p>
                    </a>
                </li>
                @endif

                @if ($user->hasAnyRole(['admin', 'receptionist']))
                <li class="nav-item">
                    <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-injured"></i>
                        <p>Patients</p>
                    </a>
                </li>
                @endif

                @if ($user->hasAnyRole(['admin', 'dentist']))
                <li class="nav-item">
                    <a href="{{ route('treatments.index') }}" class="nav-link {{ request()->routeIs('treatments.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-medical"></i>
                        <p>Treatments</p>
                    </a>
                </li>
                @endif

                @if ($user->hasAnyRole(['admin', 'receptionist', 'accountant']))
                <li class="nav-header">FINANCE</li>
                <li class="nav-item">
                    <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>Invoices</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>Payments</p>
                    </a>
                </li>
                @endif

                @if ($user->hasRole('admin'))
                <li class="nav-item">
                    <a href="{{ route('payment-methods.index') }}" class="nav-link {{ request()->routeIs('payment-methods.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>Payment Methods</p>
                    </a>
                </li>
                @endif

                @if ($user->hasAnyRole(['admin', 'accountant', 'dentist', 'receptionist']))
                <li class="nav-item">
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Reports &amp; Analytics</p>
                    </a>
                </li>
                @endif

                @if ($user->hasRole('admin'))
                <li class="nav-header">CLINIC SETUP</li>
                <li class="nav-item">
                    <a href="{{ route('dentists.index') }}" class="nav-link {{ request()->routeIs('dentists.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-md"></i>
                        <p>Dentists</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-briefcase-medical"></i>
                        <p>Services</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('schedules.index') }}" class="nav-link {{ request()->routeIs('schedules.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Schedules</p>
                    </a>
                </li>
                @endif

                @if ($user->hasAnyRole(['admin', 'accountant']))
                <li class="nav-item">
                    <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Inventory</p>
                    </a>
                </li>
                @endif

                @if ($user->hasRole('admin'))
                <li class="nav-header">ADMINISTRATION</li>
                <li class="nav-item">
                    <a href="{{ route('leads.index') }}" class="nav-link {{ request()->routeIs('leads.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-inbox"></i>
                        <p>Website Leads</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>Users &amp; Roles</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.edit') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Settings</p>
                    </a>
                </li>
                @endif

                @if ($user->hasRole('patient'))
                <li class="nav-header">MY ACCOUNT</li>
                <li class="nav-item">
                    <a href="{{ route('book-appointment') }}" class="nav-link {{ request()->routeIs('book-appointment') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-plus"></i>
                        <p>Book Appointment</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('my-appointments') }}" class="nav-link {{ request()->routeIs('my-appointments') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>My Appointments</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('my-invoices') }}" class="nav-link {{ request()->routeIs('my-invoices') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>My Invoices</p>
                    </a>
                </li>
                @endif

            </ul>
        </nav>
    </div>
</aside>
