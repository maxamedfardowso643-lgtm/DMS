<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@dcatms.test'],
            ['name' => 'System Administrator', 'username' => 'admin', 'password' => Hash::make('12345'), 'is_active' => true]
        );
        $admin->roles()->syncWithoutDetaching(Role::where('slug', Role::ADMIN)->value('id'));

        $receptionist = User::updateOrCreate(
            ['email' => 'receptionist@dcatms.test'],
            ['name' => 'Amina Yusuf', 'username' => 'receptionist', 'password' => Hash::make('12345'), 'is_active' => true]
        );
        $receptionist->roles()->syncWithoutDetaching(Role::where('slug', Role::RECEPTIONIST)->value('id'));

        $accountant = User::updateOrCreate(
            ['email' => 'accountant@dcatms.test'],
            ['name' => 'Khalid Warsame', 'username' => 'accountant', 'password' => Hash::make('12345'), 'is_active' => true]
        );
        $accountant->roles()->syncWithoutDetaching(Role::where('slug', Role::ACCOUNTANT)->value('id'));

        // Demo patient portal account
        $patientUser = User::updateOrCreate(
            ['email' => 'patient@dcatms.test'],
            ['name' => 'Demo Patient', 'username' => 'patient', 'password' => Hash::make('12345'), 'is_active' => true]
        );
        $patientUser->roles()->syncWithoutDetaching(Role::where('slug', Role::PATIENT)->value('id'));
    }
}
