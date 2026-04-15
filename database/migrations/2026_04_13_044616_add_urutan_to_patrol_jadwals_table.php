<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patrol_jadwals', function (Blueprint $table) {
            $table->integer('urutan')->default(0)->after('lapor_at');
        });
    }

    public function down(): void
    {
        Schema::table('patrol_jadwals', function (Blueprint $table) {
            $table->dropColumn('urutan');
        });
    }
};
