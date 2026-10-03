<?php

namespace App\Http\Requests;

use App\Support\DateInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('date_of_birth')) {
            $this->merge(['date_of_birth' => DateInput::toIso($this->input('date_of_birth'))]);
        }
    }

    public function rules(): array
    {
        $patientId = $this->route('patient')?->id;

        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150', Rule::unique('patients', 'email')->ignore($patientId)],
            'address' => ['nullable', 'string', 'max:500'],
            'emergency_contact_name' => ['nullable', 'string', 'max:150'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'medical_history' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
