<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('apd_items', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique()->nullable();
            $table->string('nama_barang');
            $table->string('satuan');
            $table->string('merk')->nullable();
            $table->enum('kondisi', ['baik', 'rusak', 'expired'])->default('baik');
            $table->integer('stok')->default(0);
            $table->integer('min_stok')->default(5);
            $table->boolean('is_consumable')->default(true);
            $table->date('exp_date')->nullable();
            $table->string('lokasi_gudang')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apd_items');
    }
};
