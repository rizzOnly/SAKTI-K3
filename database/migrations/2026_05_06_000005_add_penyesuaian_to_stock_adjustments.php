<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_adjustments', function (Blueprint $table) {
            // MySQL: ubah enum menambah 'penyesuaian'
            DB::statement("ALTER TABLE stock_adjustments MODIFY COLUMN tipe ENUM('tambah', 'kurang', 'penyesuaian') NOT NULL");
        });
    }

    public function down(): void
    {
        Schema::table('stock_adjustments', function (Blueprint $table) {
            DB::statement("ALTER TABLE stock_adjustments MODIFY COLUMN tipe ENUM('tambah', 'kurang') NOT NULL");
        });
    }
};
