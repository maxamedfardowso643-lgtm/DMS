<?php

namespace Database\Seeders;

use App\Models\Dentist;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DentistSeeder extends Seeder
{
    public function run(): void
    {
        $dentists = [
            [
                'name' => 'Dr. Abdirahman Mohamed Ali',
                'specialization' => 'General Dentistry',
                'phone' => '+2529012345',
            ],
            [
                'name' => 'Dr. Hodan Yusuf Warsame',
                'specialization' => 'Orthodontics',
                'phone' => '+2526112233',
            ],
            [
                'name' => 'Dr. Khalid Ahmed Farah',
                'specialization' => 'Oral Surgery',
                'phone' => '+2526898765',
            ],
        ];

        $roleId = Role::where('slug', Role::DENTIST)->value('id');

        foreach ($dentists as $i => $d) {
            $email = 'dentist' . ($i + 1) . '@dcatms.test';

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $d['name'],
                    'username' => 'dentist' . ($i + 1),
                    'phone' => $d['phone'],
                    'password' => Hash::make('12345'),
                    'is_active' => true,
                ]
            );
            $user->roles()->syncWithoutDetaching($roleId);

            Dentist::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'dentist_code' => 'DEN-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'specialization' => $d['specialization'],
                    'license_number' => 'LIC-' . rand(10000, 99999),
                    'bio' => "{$d['name']} specializes in {$d['specialization']}, based at the clinic in Garowe, Puntland.",
                    'is_active' => true,
                ]
            );
        }
    }
}
