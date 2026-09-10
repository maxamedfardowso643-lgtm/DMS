<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dentist_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['weekly', 'leave'])->default('weekly');
            // weekly recurring availability
            $table->tinyInteger('day_of_week')->nullable(); // 0=Sunday ... 6=Saturday
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            // one-off leave/holiday override
            $table->date('leave_date')->nullable();
            $table->string('reason')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['dentist_id', 'day_of_week']);
            $table->index(['dentist_id', 'leave_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
