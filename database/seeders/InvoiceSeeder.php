<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $appointments = Appointment::where('status', 'completed')->with('service')->get();
        $faker = \Faker\Factory::create();
        $year = date('Y');
        $i = 0;

        foreach ($appointments as $appointment) {
            $i++;
            $service = $appointment->service;
            $qty = 1;
            $unitPrice = (float) $service->price;
            $discount = $faker->boolean(20) ? round($unitPrice * 0.1, 2) : 0;
            $tax = 0;
            $lineTotal = ($unitPrice * $qty) - $discount + $tax;

            $subtotal = $unitPrice * $qty;
            $totalAmount = $lineTotal;

            $issueDate = Carbon::parse($appointment->appointment_date);

            $paymentChoice = $faker->randomElement(['paid', 'paid', 'partially_paid', 'unpaid']);
            $paidAmount = match ($paymentChoice) {
                'paid' => $totalAmount,
                'partially_paid' => round($totalAmount * 0.5, 2),
                default => 0,
            };

            $invoice = Invoice::create([
                'invoice_no' => 'INV-' . $year . '-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'issue_date' => $issueDate->format('Y-m-d'),
                'due_date' => $issueDate->copy()->addDays(14)->format('Y-m-d'),
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'tax_amount' => $tax,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'status' => $paymentChoice,
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'service_id' => $service->id,
                'description' => $service->name,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'discount_amount' => $discount,
                'tax_amount' => $tax,
                'line_total' => $lineTotal,
            ]);

            if ($paidAmount > 0) {
                Payment::create([
                    'payment_no' => 'PAY-' . $year . '-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                    'invoice_id' => $invoice->id,
                    'patient_id' => $invoice->patient_id,
                    'payment_date' => $issueDate->format('Y-m-d'),
                    'amount' => $paidAmount,
                    'method' => $faker->randomElement(['cash', 'card', 'mobile_money', 'bank_transfer']),
                    'reference_no' => strtoupper($faker->bothify('REF-####??')),
                    'type' => 'payment',
                ]);
            }

            if ($i >= 35) {
                break;
            }
        }
    }
}
