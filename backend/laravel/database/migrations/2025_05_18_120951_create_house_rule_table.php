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
        Schema::create('house_rule', function (Blueprint $table) {
            $table->id();
            // Foreign key to owner_applications
            $table->unsignedBigInteger('application_id');
            $table->foreign('application_id')->references('id')->on('owner_applications')->onDelete('cascade');

            // Check-in and check-out times
            $table->time('checkin_from')->nullable();   // e.g., 14:00
            $table->time('checkout_from')->nullable();  // e.g., 08:00
            $table->time('checkout_to')->nullable();    // e.g., 11:00
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_rule');
    }
};
