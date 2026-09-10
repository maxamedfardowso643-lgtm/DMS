<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_no')->unique(); // PAY-2026-00001
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->date('payment_date');
            $table->decimal('amount', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->string('method')->default('cash'); // denormalized label at time of payment
            $table->string('sender_phone')->nullable(); // number money was sent from (mobile money)
            $table->string('reference_no')->nullable(); // transaction ref
            $table->enum('type', ['payment', 'refund', 'credit_note'])->default('payment');
            $table->text('notes')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('invoice_id');
            $table->index('patient_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
