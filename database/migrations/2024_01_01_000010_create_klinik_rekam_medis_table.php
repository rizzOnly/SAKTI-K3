<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('klinik_rekam_medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->nullable()->constrained('klinik_appointments');
            $table->foreignId('user_id')->constrained('users'); // pasien
            $table->foreignId('dokter_id')->constrained('users'); // dokter
            $table->text('diagnosa');
            $table->text('tindakan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klinik_rekam_medis');
    }
};
