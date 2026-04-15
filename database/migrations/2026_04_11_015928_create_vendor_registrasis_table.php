<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vendor_registrasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->string('nama_pekerjaan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');                     // masa berlaku = durasi kerja
            $table->string('no_wa_pic')->nullable();             // kontak PIC
            $table->string('email_pic')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'expired'])->default('aktif');
            $table->boolean('is_active')->default(true);         // admin bisa nonaktifkan
            $table->string('token_registrasi')->unique()->nullable(); // untuk akses halaman survey
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('vendor_registrasis'); }
};
