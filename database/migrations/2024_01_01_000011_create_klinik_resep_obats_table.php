<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('klinik_resep_obats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekam_medis_id')->constrained('klinik_rekam_medis')->cascadeOnDelete();
            $table->foreignId('obat_id')->constrained('klinik_obats');
            $table->integer('jumlah');
            $table->string('aturan_pakai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klinik_resep_obats');
    }
};
