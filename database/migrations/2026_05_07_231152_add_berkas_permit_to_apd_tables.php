<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengambilan_headers', function (Blueprint $table) {
            $table->string('berkas_permit')->nullable()->after('catatan');
        });

        Schema::table('peminjaman_headers', function (Blueprint $table) {
            $table->string('berkas_jsa')->nullable()->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('pengambilan_headers', function (Blueprint $table) {
            $table->dropColumn('berkas_permit');
        });
        Schema::table('peminjaman_headers', function (Blueprint $table) {
            $table->dropColumn('berkas_jsa');
        });
    }
};
