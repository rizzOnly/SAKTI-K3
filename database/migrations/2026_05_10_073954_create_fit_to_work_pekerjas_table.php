<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Hapus kolom yang dipindah ke tabel pekerja
        Schema::table('fit_to_works', function (Blueprint $table) {
            $table->dropColumn([
                'nama', 'jenis_kelamin',
                'status', 'catatan_dokter',
                'tanggal_periksa', 'dokter_nama',
            ]);
        });

        // Tabel pekerja per submission
        Schema::create('fit_to_work_pekerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fit_to_work_id')
                  ->constrained('fit_to_works')->cascadeOnDelete();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->enum('status', ['menunggu', 'fit', 'tidak_fit'])->default('menunggu');
            $table->text('catatan_dokter')->nullable();
            $table->date('tanggal_periksa')->nullable();
            $table->string('dokter_nama')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fit_to_work_pekerjas');
    }
};
