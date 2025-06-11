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
        Schema::create('commission', function (Blueprint $table) {
            $table->id();
                // Foreign key to owner_applications
            $table->unsignedBigInteger('application_id');
            $table->foreign('application_id')->references('id')->on('owner_applications')->onDelete('cascade');

            $table->decimal('total_commission', 10, 2); // e.g. 1234.56
            $table->date('month'); // Store year-month, e.g. 2024-05-01 (just the first day of the month)
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission');
    }
};
