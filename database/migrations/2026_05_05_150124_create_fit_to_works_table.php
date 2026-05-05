<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fit_to_works', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['internal', 'vendor']);        // pegawai internal atau vendor
            $table->string('nama');
            $table->string('nama_perusahaan')->nullable();       // diisi jika vendor
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('nama_pekerjaan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');                     // durasi kerja
            $table->string('no_wa')->nullable();

            // Hasil pemeriksaan dokter
            $table->enum('status', ['menunggu', 'fit', 'tidak_fit'])->default('menunggu');
            $table->text('catatan_dokter')->nullable();
            $table->date('tanggal_periksa')->nullable();
            $table->string('dokter_nama')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fit_to_works');
    }
};
