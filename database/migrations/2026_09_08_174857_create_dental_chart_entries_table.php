<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dental_chart_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('treatment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tooth_number'); // FDI notation 11-48
            $table->enum('condition', [
                'healthy', 'decayed', 'filled', 'missing', 'crowned',
                'root_canal', 'implant', 'extracted', 'impacted',
            ])->default('healthy');
            $table->text('notes')->nullable();
            $table->date('recorded_date');
            $table->timestamps();

            $table->index(['patient_id', 'tooth_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dental_chart_entries');
    }
};
