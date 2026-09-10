<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'clinic_name' => 'DCATMS Dental Clinic',
            'clinic_phone' => '+252 61 0000000',
            'clinic_email' => 'info@dcatms.test',
            'clinic_address' => 'Mogadishu, Somalia',
            'working_hours' => '09:00 - 17:00 (Sun - Thu)',
            'currency' => 'USD',
            'currency_symbol' => '$',
            'tax_rate' => '0',
            'invoice_prefix' => 'INV',
            'appointment_prefix' => 'APT',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
