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
            $table->string('currency', 3)->default('KHR')->after('amount');
            $table->string('merchant_name')->nullable()->after('currency');
            $table->timestamp('expires_at')->nullable()->after('status');
            $table->json('response_data')->nullable()->after('expires_at');
            $table->string('booking_reference')->nullable()->after('response_data');
            
            // Add indexes for better performance
            $table->index(['status', 'created_at']);
            $table->index('qr_md5');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'currency',
                'merchant_name', 
                'expires_at',
                'response_data',
                'booking_reference'
            ]);
            
            $table->dropIndex(['transactions_status_created_at_index']);
            $table->dropIndex(['transactions_qr_md5_index']);
        });
    }
};
