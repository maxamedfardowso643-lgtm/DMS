<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'slug' => Role::ADMIN, 'description' => 'Full system access'],
            ['name' => 'Dentist', 'slug' => Role::DENTIST, 'description' => 'Clinical staff'],
            ['name' => 'Receptionist', 'slug' => Role::RECEPTIONIST, 'description' => 'Front desk / appointments'],
            ['name' => 'Accountant', 'slug' => Role::ACCOUNTANT, 'description' => 'Billing and finance'],
            ['name' => 'Patient', 'slug' => Role::PATIENT, 'description' => 'Patient portal access'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
