<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ServiceSeeder::class,
            DentistSeeder::class,
            ScheduleSeeder::class,
            PatientSeeder::class,
            PaymentMethodSeeder::class,
            SettingSeeder::class,
            InventorySeeder::class,
            AppointmentSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
