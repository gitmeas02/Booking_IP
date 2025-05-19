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
        Schema::create('application_location', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_application_id'); // FK to track ownership
            $table->foreign('owner_application_id')->references('id')->on('owner_applications')->onDelete('cascade');
            $table->string('apartment_floor')->nullable(); // optional if not every property has a floor
            $table->string('country');
            $table->string('city');
            $table->string('postcode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_location');
    }
};
