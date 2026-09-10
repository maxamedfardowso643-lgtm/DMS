<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    public function index(): View
    {
        $methods = PaymentMethod::orderBy('sort_order')->get();

        return view('payment-methods.index', compact('methods'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('payment-methods.index');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:50', Rule::unique('payment_methods', 'code')],
            'account_number' => ['nullable', 'string', 'max:100'],
            'account_name' => ['nullable', 'string', 'max:150'],
        ]);

        PaymentMethod::create($data + ['is_active' => true, 'sort_order' => PaymentMethod::count() + 1]);

        return response()->json(['message' => 'Payment method added successfully.'], 201);
    }

    public function edit(PaymentMethod $paymentMethod): JsonResponse
    {
        return response()->json($paymentMethod);
    }

    public function update(Request $request, PaymentMethod $paymentMethod): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'account_number' => ['nullable', 'string', 'max:100'],
            'account_name' => ['nullable', 'string', 'max:150'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $paymentMethod->update($data + ['is_active' => $request->boolean('is_active', true)]);

        return response()->json(['message' => 'Payment method updated successfully.']);
    }

    public function destroy(PaymentMethod $paymentMethod): JsonResponse
    {
        if ($paymentMethod->payments()->exists()) {
            return response()->json(['message' => 'Cannot delete a payment method that has been used.'], 422);
        }

        $paymentMethod->delete();

        return response()->json(['message' => 'Payment method deleted successfully.']);
    }
}
