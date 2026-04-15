<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('peminjaman_headers', function (Blueprint $table) {
            $table->dropColumn('denda');
        });
        // Ubah enum MySQL langsung (Hati-hati, pastikan tabelnya memang ada)
        DB::statement("ALTER TABLE peminjaman_headers MODIFY kondisi_kembali ENUM('baik','rusak','hilang') NULL");
    }

    public function down(): void
    {
        Schema::table('peminjaman_headers', function (Blueprint $table) {
            $table->decimal('denda', 10, 2)->default(0);
        });
    }
};
