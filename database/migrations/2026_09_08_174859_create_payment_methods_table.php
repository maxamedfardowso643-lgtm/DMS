<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Cash, e-Dahab, Sahal (EVC Plus), Bank Transfer...
            $table->string('code')->unique(); // cash, edahab, sahal, bank_transfer
            $table->string('account_number')->nullable(); // hospital account / mobile number that receives payment
            $table->string('account_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
