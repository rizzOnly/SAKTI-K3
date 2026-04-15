<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cms_vendors', function (Blueprint $table) {
            // Kolom JSON untuk menyimpan daftar nama pekerja WPO Plus
            $table->json('pekerja_json')->nullable()->after('kategori');
        });
    }

    public function down(): void
    {
        Schema::table('cms_vendors', function (Blueprint $table) {
            $table->dropColumn('pekerja_json');
        });
    }
};
