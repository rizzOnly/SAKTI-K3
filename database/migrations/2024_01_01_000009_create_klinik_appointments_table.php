<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('klinik_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // pasien
            $table->foreignId('dokter_id')->constrained('users'); // dokter
            $table->date('tanggal');
            $table->string('jam_slot'); // e.g. "08:00", "08:30"
            $table->text('keluhan')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamps();

            // Unique constraint anti-collision: 1 dokter 1 slot per tanggal
            $table->unique(['dokter_id', 'tanggal', 'jam_slot']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klinik_appointments');
    }
};
