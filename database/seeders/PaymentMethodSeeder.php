<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'Cash', 'code' => 'cash', 'account_number' => null, 'account_name' => null, 'sort_order' => 1],
            ['name' => 'e-Dahab', 'code' => 'edahab', 'account_number' => '252-65-0000000', 'account_name' => 'DCATMS Dental Clinic', 'sort_order' => 2],
            ['name' => 'Sahal (EVC Plus)', 'code' => 'sahal', 'account_number' => '252-61-0000000', 'account_name' => 'DCATMS Dental Clinic', 'sort_order' => 3],
            ['name' => 'Bank Transfer', 'code' => 'bank_transfer', 'account_number' => 'SO00 0000 0000 0000', 'account_name' => 'DCATMS Dental Clinic', 'sort_order' => 4],
        ];

        foreach ($methods as $m) {
            PaymentMethod::updateOrCreate(['code' => $m['code']], $m + ['is_active' => true]);
        }
    }
}
