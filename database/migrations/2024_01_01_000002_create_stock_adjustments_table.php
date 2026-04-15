<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apd_item_id')->constrained('apd_items')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('tipe', ['tambah', 'kurang']);
            $table->integer('jumlah');
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
