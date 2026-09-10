<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\AppointmentStatusLog;
use App\Models\Attachment;
use App\Models\DentalChartEntry;
use App\Models\Dentist;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\StockMovement;
use App\Models\Treatment;
use App\Models\TreatmentDetail;
use App\Models\User;
use App\Models\InventoryItem;
use App\Models\ActivityLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetDemoData extends Command
{
    protected $signature = 'app:reset-demo-data {--keep-services : Keep the seeded service catalogue}';

    protected $description = 'Wipe all demo/seed data (patients, dentists, appointments, invoices, payments, users...) keeping only the admin accounts.';

    protected array $keepEmails = ['admin@dcatms.test', 'fadowso@gmail.com'];

    public function handle(): int
    {
        if (! $this->confirm('This will permanently delete ALL demo data (patients, dentists, appointments, invoices, payments, and every user except the two admin accounts). Continue?', false)) {
            $this->info('Cancelled.');
            return self::SUCCESS;
        }

        DB::transaction(function () {
            AppointmentStatusLog::query()->delete();
            Prescription::query()->delete();
            TreatmentDetail::query()->delete();
            Treatment::withTrashed()->get()->each->forceDelete();
            DentalChartEntry::query()->delete();
            Attachment::query()->delete();
            InvoiceItem::query()->delete();
            Payment::withTrashed()->get()->each->forceDelete();
            Invoice::withTrashed()->get()->each->forceDelete();
            Appointment::withTrashed()->get()->each->forceDelete();
            Schedule::query()->delete();
            Dentist::withTrashed()->get()->each->forceDelete();
            Patient::withTrashed()->get()->each->forceDelete();
            StockMovement::query()->delete();
            InventoryItem::withTrashed()->get()->each->forceDelete();

            if (! $this->option('keep-services')) {
                Service::withTrashed()->get()->each->forceDelete();
            }

            ActivityLog::query()->delete();

            // Keep only the designated admin accounts — hard delete everyone else.
            User::withTrashed()->whereNotIn('email', $this->keepEmails)->get()->each->forceDelete();

            $adminRoleId = Role::where('slug', Role::ADMIN)->value('id');

            $admin = User::firstOrCreate(
                ['email' => 'admin@dcatms.test'],
                ['name' => 'System Administrator', 'password' => Hash::make('password'), 'is_active' => true]
            );
            $admin->roles()->sync([$adminRoleId]);

            $fadowso = User::firstOrCreate(
                ['email' => 'fadowso@gmail.com'],
                ['name' => 'Fadowso', 'password' => Hash::make('password'), 'is_active' => true]
            );
            $fadowso->roles()->sync([$adminRoleId]);
        });

        $this->info('Demo data wiped. Remaining admin accounts:');
        $this->table(['Name', 'Email'], User::all(['name', 'email'])->map(fn ($u) => [$u->name, $u->email])->toArray());
        $this->warn('Default password for both admin accounts is "password" — change it from Users & Roles.');

        return self::SUCCESS;
    }
}
