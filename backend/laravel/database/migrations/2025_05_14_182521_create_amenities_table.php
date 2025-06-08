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
        Schema::create('amenity_groups', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
        });
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('amenity_name');
            $table->foreignId('group_id')->constrained('amenity_groups')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('amenity_room_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained('room_type')->onDelete('cascade');
            $table->foreignId('amenity_id')->constrained('amenities')->onDelete('cascade');
            $table->timestamps();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('amenity_room_type');
        Schema::dropIfExists('amenity_groups');
    }
};
