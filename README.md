# Dental Clinic Appointment Tracking Management System (DCATMS)

A Laravel 11 + Bootstrap 5 + AdminLTE 3 web application for managing dental clinic appointments, invoices, billing, patients, dentists, treatments and inventory.

## Tech Stack

- Laravel 11 (PHP 8.2+)
- MySQL (XAMPP)
- Blade + Bootstrap 5 + AdminLTE 3
- jQuery 3, SweetAlert2, Toastr, DataTables, Chart.js, FullCalendar 6
- barryvdh/laravel-dompdf (PDF invoices)
- maatwebsite/excel (Excel export)

## Requirements

- XAMPP (PHP 8.2+, MySQL/MariaDB, Apache)
- Composer
- Node.js (optional, only needed if you want to build local frontend assets — the app uses CDN links by default)

## Setup Instructions

1. **Start XAMPP** — make sure Apache and MySQL are running.

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Configure environment**

   Copy `.env.example` to `.env` (already done if you cloned this repo) and set:

   ```
   APP_NAME="DCATMS"
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=dcatms_clinic
   DB_USERNAME=root
   DB_PASSWORD=
   DB_CHARSET=utf8mb4
   DB_COLLATION=utf8mb4_unicode_ci
   ```

   > Note: `DB_COLLATION=utf8mb4_unicode_ci` is required because XAMPP ships MariaDB, which does not support MySQL 8's default `utf8mb4_0900_ai_ci` collation.

4. **Generate the application key**

   ```bash
   php artisan key:generate
   ```

5. **Create the database**

   Using phpMyAdmin or the MySQL CLI:

   ```sql
   CREATE DATABASE dcatms_clinic CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

   Alternatively, import the provided full dump at `database/dcatms.sql` (schema + demo data) directly via phpMyAdmin.

6. **Run migrations**

   ```bash
   php artisan migrate
   ```

   Then seed the base configuration data only (roles, default payment methods, clinic settings):

   ```bash
   php artisan db:seed --class=RoleSeeder
   php artisan db:seed --class=PaymentMethodSeeder
   php artisan db:seed --class=SettingSeeder
   ```

   Create the two admin accounts via tinker:

   ```bash
   php artisan tinker --execute="
   \$roleId = \App\Models\Role::where('slug','admin')->value('id');
   \$a = \App\Models\User::firstOrCreate(['email'=>'admin@dcatms.test'],['name'=>'System Administrator','password'=>bcrypt('password'),'is_active'=>true]);
   \$a->roles()->sync([\$roleId]);
   "
   ```

   Want realistic demo data instead (patients, dentists, appointments, invoices, payments) to explore the UI? Run `php artisan db:seed` for the full demo dataset, then wipe it later with `php artisan app:reset-demo-data` (keeps only the admin accounts you designate in that command).

7. **Link the storage disk** (for patient photo uploads)

   ```bash
   php artisan storage:link
   ```

8. **Run the app**

   ```bash
   php artisan serve
   ```

   Visit **http://127.0.0.1:8000**

## Default Login Credentials

| Role  | Email               | Password   |
|-------|---------------------|------------|
| Admin | admin@dcatms.test   | `password` |
| Admin | fadowso@gmail.com   | `password` |

Change these from **My Profile** (top-right avatar dropdown) after first login. Additional staff accounts (receptionist, accountant, dentist) are created from **Users & Roles** / **Dentists** in the app once you're logged in.

## Modules

- **Appointments** — calendar (month/week/day, drag-and-drop reschedule) + list view, walk-in vs scheduled, automatic slot generation based on service duration, double-booking prevention, full status timeline (booked → confirmed → checked-in → in-progress → completed / cancelled / no-show).
- **Invoices** — dynamic line items with live subtotal/discount/tax/total calculation, printable/downloadable PDF, editable while draft/unpaid.
- **Payments & Billing** — full/partial payments, multiple payment methods, automatic invoice status recalculation, refunds/credit notes.
- **Patients** — registration, medical history, allergies, interactive 32-tooth dental chart, appointment & invoice history.
- **Dentists & Schedules** — profile, specialization, weekly working hours, leave/holiday management.
- **Treatments** — clinical notes per visit, procedures performed, prescriptions, dental chart updates.
- **Inventory** — stock items, stock in/out movements, low-stock dashboard alerts.
- **Reports** — appointment volume, cancellation/no-show rate, revenue by dentist/service, aging receivables, Excel export.
- **Settings** — clinic profile, currency, tax rate, invoice/appointment number prefixes.
- **RBAC** — Admin, Dentist, Receptionist, Accountant, Patient roles with route-level and UI-level access control.

## Notes on This Environment

This project was scaffolded and developed against a local XAMPP install at `C:\Xampp_Folder` (not the default `C:\xampp` path) — if PHP/MySQL commands aren't found in a fresh terminal, add `C:\Xampp_Folder\php` and `C:\Xampp_Folder\mysql\bin` to your PATH, or use the full executable paths.

## PSR-12 / Conventions

Controllers use Laravel resource conventions; AJAX endpoints return JSON for DataTables server-side processing and modal-based CRUD. Form validation is handled via dedicated `FormRequest` classes in `app/Http/Requests`.
