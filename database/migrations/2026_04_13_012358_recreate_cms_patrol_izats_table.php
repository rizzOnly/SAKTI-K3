<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Drop tabel lama
        Schema::dropIfExists('cms_patrol_izats');

        // Tabel periode (1 record = 1 bulan jadwal)
        Schema::create('patrol_periodes', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable(); // misal "Jadwal Patrol April 2026"
            $table->tinyInteger('bulan');        // 1-12
            $table->smallInteger('tahun');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['bulan', 'tahun']);
        });

        // Tabel jadwal per orang per tanggal
        Schema::create('patrol_jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patrol_periode_id')
                  ->constrained('patrol_periodes')->cascadeOnDelete();
            $table->string('nama_petugas');
            $table->date('tanggal_patrol');      // tanggal spesifik
            $table->string('lokasi_unit')->nullable(); // GT 22, ST18, COMMON 1, dll
            $table->boolean('sudah_lapor')->default(false);
            $table->timestamp('lapor_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrol_jadwals');
        Schema::dropIfExists('patrol_periodes');
    }
};
