<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apd_inspections', function (Blueprint $table) {
            $table->id();
            $table->string('bulan', 7); // Format: YYYY-MM (e.g., 2026-05)
            $table->date('tanggal_inspeksi');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->timestamps();

            $table->unique(['bulan']); // One inspection per month
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apd_inspections');
    }
};
