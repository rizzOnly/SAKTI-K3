<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjaman_headers', function (Blueprint $table) {
            // Tracking kapan notifikasi dikirim (hindari spam)
            $table->timestamp('reminder_h1_sent_at')->nullable()->after('returned_at');
            $table->timestamp('reminder_jatuh_tempo_sent_at')->nullable()->after('reminder_h1_sent_at');
            $table->timestamp('reminder_terlambat_last_sent_at')->nullable()->after('reminder_jatuh_tempo_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_headers', function (Blueprint $table) {
            $table->dropColumn([
                'reminder_h1_sent_at',
                'reminder_jatuh_tempo_sent_at',
                'reminder_terlambat_last_sent_at',
            ]);
        });
    }
};
