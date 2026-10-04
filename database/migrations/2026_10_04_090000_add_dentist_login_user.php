<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Shared "dentist" login (like admin / receptionist / accountant), password 12345.
     */
    public function up(): void
    {
        if (User::withTrashed()->where('username', 'dentist')->orWhere('email', 'dentist@dcatms.test')->exists()) {
            return;
        }

        $user = User::create([
            'name' => 'Dentist',
            'username' => 'dentist',
            'email' => 'dentist@dcatms.test',
            'password' => Hash::make('12345'),
            'is_active' => true,
        ]);

        if ($roleId = Role::where('slug', Role::DENTIST)->value('id')) {
            $user->roles()->syncWithoutDetaching($roleId);
        }
    }

    public function down(): void
    {
        User::where('username', 'dentist')->forceDelete();
    }
};
