<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->foreignId('apd_inspection_id')
                ->nullable()
                ->after('user_id')
                ->constrained('apd_inspections')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->dropForeign(['apd_inspection_id']);
            $table->dropColumn('apd_inspection_id');
        });
    }
};
