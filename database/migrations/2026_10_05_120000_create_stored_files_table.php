<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Copies of uploaded images, so they survive redeploys on hosts whose
     * disk is wiped each time (Railway has no volume on the app service).
     */
    public function up(): void
    {
        Schema::create('stored_files', function (Blueprint $table) {
            $table->id();
            $table->string('path')->unique();
            $table->string('mime_type', 100);
            $table->binary('contents');
            $table->timestamps();
        });

        // A plain BLOB caps out at 64 KB; photos are bigger.
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE stored_files MODIFY contents LONGBLOB NOT NULL');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stored_files');
    }
};
