<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'dentist_id' => ['required', 'exists:dentists,id'],
            'service_id' => ['required', 'exists:services,id'],
            'appointment_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'source' => ['required', Rule::in(['scheduled', 'walk_in'])],
            'notes' => ['nullable', 'string'],
        ];
    }
}
