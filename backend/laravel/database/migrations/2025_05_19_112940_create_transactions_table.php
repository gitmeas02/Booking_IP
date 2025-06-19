<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('bakong_account_id');
            $table->string('qr_string', 1024);
            $table->string('qr_md5');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, success, failed
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('transactions');
    }
};
