<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use App\Support\DateInput;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        if ($request->filled('date_of_birth')) {
            $request->merge(['date_of_birth' => DateInput::toIso($request->input('date_of_birth'))]);
        }

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')],
            'phone' => ['required', 'string', 'max:30'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'address' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => trim("{$data['first_name']} {$data['last_name']}"),
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'],
                'is_active' => true,
            ]);

            $user->roles()->attach(Role::where('slug', Role::PATIENT)->value('id'));

            Patient::create([
                'user_id' => $user->id,
                'patient_code' => $this->nextCode(),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'] ?? null,
                'is_active' => true,
            ]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    protected function nextCode(): string
    {
        $year = date('Y');
        $last = Patient::where('patient_code', 'like', "PT-$year-%")
            ->withTrashed()->orderByDesc('patient_code')->value('patient_code');
        $next = $last ? ((int) substr($last, -5)) + 1 : 1;

        return "PT-$year-" . str_pad($next, 5, '0', STR_PAD_LEFT);
    }
}
