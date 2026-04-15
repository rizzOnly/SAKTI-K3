<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengambilan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengambilan_header_id')->constrained('pengambilan_headers')->cascadeOnDelete();
            $table->foreignId('apd_item_id')->constrained('apd_items');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengambilan_details');
    }
};
