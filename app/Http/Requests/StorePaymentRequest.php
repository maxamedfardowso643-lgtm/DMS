<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_id' => ['required', 'exists:invoices,id'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'sender_phone' => ['nullable', 'string', 'max:30'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'type' => ['required', Rule::in(['payment', 'refund', 'credit_note'])],
            'notes' => ['nullable', 'string'],
        ];
    }
}
