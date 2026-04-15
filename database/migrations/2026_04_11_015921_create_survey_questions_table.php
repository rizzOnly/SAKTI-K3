<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->text('pertanyaan');                          // teks soal
            $table->string('gambar_soal')->nullable();           // gambar soal (opsional)
            $table->integer('urutan')->default(0);               // urutan tampil
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('survey_questions'); }
};
