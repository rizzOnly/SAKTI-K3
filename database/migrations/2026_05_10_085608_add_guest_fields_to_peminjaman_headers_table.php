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
            $table->boolean('is_guest')->default(false)->after('user_id');
            $table->string('guest_nama')->nullable()->after('is_guest');
            $table->string('guest_perusahaan')->nullable()->after('guest_nama');
            $table->string('guest_no_wa')->nullable()->after('guest_perusahaan');
            $table->string('guest_email')->nullable()->after('guest_no_wa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_headers', function (Blueprint $table) {
            $table->dropColumn(['is_guest', 'guest_nama', 'guest_perusahaan', 'guest_no_wa', 'guest_email']);
        });
    }
};
