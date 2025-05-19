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
        Schema::create('amenities_room_type', function (Blueprint $table) {
            $table->id();
            // Foreign key to room_types (assuming your rooms table is called room_types)
            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')->references('id')->on('room_type')->onDelete('cascade');

            // Foreign key to amenities
            $table->unsignedBigInteger('amenity_id');
            $table->foreign('amenity_id')->references('id')->on('amenities')->onDelete('cascade');

            $table->timestamps();

            // Prevent duplicate entries
            $table->unique(['room_id', 'amenity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities_room_type');
    }
};
