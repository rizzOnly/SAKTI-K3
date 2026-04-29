<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('apd_items', function (Blueprint $table) {
            $table->dropColumn('lokasi_gudang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apd_items', function (Blueprint $table) {
            $table->string('lokasi_gudang')->nullable()->after('exp_date');
        });
    }
};
