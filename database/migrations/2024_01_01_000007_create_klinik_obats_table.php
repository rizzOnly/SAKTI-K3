<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('klinik_obats', function (Blueprint $table) {
            $table->id();
            $table->string('kode_obat')->unique()->nullable();
            $table->string('nama_barang');
            $table->string('satuan');
            $table->integer('stok')->default(0);
            $table->integer('min_stok')->default(10);
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_exp')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klinik_obats');
    }
};
