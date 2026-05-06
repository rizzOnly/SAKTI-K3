<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apd_inspection_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apd_inspection_id')->constrained('apd_inspections')->cascadeOnDelete();
            $table->foreignId('apd_item_id')->constrained('apd_items')->cascadeOnDelete();
            $table->enum('kondisi_sebelum', ['baik', 'rusak', 'expired']);
            $table->enum('kondisi_sesudah', ['baik', 'rusak', 'expired']);
            $table->boolean('is_tidak_layak')->default(false);
            $table->text('alasan_tidak_layak')->nullable();
            // Store checklist criteria results as JSON
            // Example: [{"nama": "Tali Helm", "lantai": true, "keterangan": "terputus"}, ...]
            $table->json('kriteria_ceklist')->nullable();
            $table->timestamps();

            // Ensure each item is only inspected once per inspection
            $table->unique(['apd_inspection_id', 'apd_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apd_inspection_details');
    }
};
