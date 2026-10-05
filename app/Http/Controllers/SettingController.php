<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Setting;
use App\Support\Uploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $keys = [
            'clinic_name', 'clinic_phone', 'clinic_email', 'clinic_address',
            'working_hours', 'currency', 'currency_symbol', 'tax_rate',
            'invoice_prefix', 'appointment_prefix', 'clinic_logo',
        ];

        $settings = collect($keys)->mapWithKeys(fn ($key) => [$key => $key === 'clinic_logo' ? Setting::logo() : Setting::get($key)]);

        return view('settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'clinic_name' => ['required', 'string', 'max:150'],
            'clinic_phone' => ['nullable', 'string', 'max:30'],
            'clinic_email' => ['nullable', 'email', 'max:150'],
            'clinic_address' => ['nullable', 'string'],
            'working_hours' => ['nullable', 'string', 'max:150'],
            'currency' => ['required', 'string', 'max:10'],
            'currency_symbol' => ['required', 'string', 'max:5'],
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'invoice_prefix' => ['required', 'string', 'max:10'],
            'appointment_prefix' => ['required', 'string', 'max:10'],
            'logo' => ['nullable', 'image', 'max:1024'],
        ]);

        if ($request->hasFile('logo')) {
            $data['clinic_logo'] = Uploads::store($request->file('logo'), 'branding');
        }
        unset($data['logo']);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        ActivityLog::log('updated', 'Clinic settings updated');

        return back()->with('success', 'Settings updated successfully.');
    }
}
