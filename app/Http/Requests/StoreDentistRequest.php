<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDentistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $dentistId = $this->route('dentist')?->id;
        $userId = $this->route('dentist')?->user_id;

        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password' => [$dentistId ? 'nullable' : 'required', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:30'],
            'specialization' => ['nullable', 'string', 'max:150'],
            'license_number' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
