<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('survey_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_question_id')
                  ->constrained('survey_questions')->cascadeOnDelete();
            $table->string('teks_opsi');                         // label opsi
            $table->string('gambar_opsi')->nullable();           // gambar opsi (opsional)
            $table->boolean('is_benar')->default(false);         // hanya 1 yg benar per soal
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('survey_options'); }
};
