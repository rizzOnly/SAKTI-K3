<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vendor_registrasis', function (Blueprint $table) {
            // Referensi ke vendor WPO Plus yang jadi sumbernya
            $table->foreignId('cms_vendor_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('cms_vendors')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('vendor_registrasis', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\CmsVendor::class);
            $table->dropColumn('cms_vendor_id');
        });
    }
};
