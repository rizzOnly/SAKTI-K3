<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Rename / replace tabel cms_vendors yang lama menjadi cms_vendor_wpos
        // ATAU bisa tambah kolom 'kategori' ke cms_vendors yang sudah ada:
        Schema::table('cms_vendors', function (Blueprint $table) {
            $table->enum('kategori', ['wpo_plus', 'gate_access'])
                  ->default('wpo_plus')
                  ->after('is_active');
            $table->string('nama_pekerjaan')->nullable()->after('nama_vendor');
            $table->date('tanggal_mulai')->nullable()->after('nama_pekerjaan');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
        });
    }
    public function down(): void
    {
        Schema::table('cms_vendors', function (Blueprint $table) {
            $table->dropColumn(['kategori','nama_pekerjaan','tanggal_mulai','tanggal_selesai']);
        });
    }
};
