<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->date('preferred_date')->nullable();
            $table->string('preferred_time')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'contacted', 'converted', 'dismissed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_requests');
    }
};
