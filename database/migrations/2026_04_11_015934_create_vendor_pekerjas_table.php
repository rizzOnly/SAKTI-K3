<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vendor_pekerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_registrasi_id')
                  ->constrained('vendor_registrasis')->cascadeOnDelete();
            $table->string('nama_pekerja');
            $table->string('foto_pekerja')->nullable();

            // Survey
            $table->boolean('survey_lulus')->default(false);
            $table->integer('survey_skor')->default(0);          // persen (0-100)
            $table->timestamp('survey_lulus_at')->nullable();
            $table->integer('survey_attempt')->default(0);       // berapa kali percobaan
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('vendor_pekerjas'); }
};
