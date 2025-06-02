6
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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('qr_full_hash')->nullable()->after('qr_md5'); // SHA256 hash (64 characters)
            $table->string('qr_short_hash')->nullable()->after('qr_full_hash'); // Short hash (8 characters)
            
            // Add indexes for better performance
            $table->index('qr_full_hash');
            $table->index('qr_short_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['qr_full_hash']);
            $table->dropIndex(['qr_short_hash']);
            $table->dropColumn(['qr_full_hash', 'qr_short_hash']);
        });
    }
};
