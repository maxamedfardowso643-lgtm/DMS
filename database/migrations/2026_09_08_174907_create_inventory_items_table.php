<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();
            $table->string('name');
            $table->string('unit')->nullable(); // box, pcs, ml
            $table->unsignedInteger('quantity_on_hand')->default(0);
            $table->unsignedInteger('reorder_level')->default(10);
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->string('supplier')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
