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
        Schema::create('room_types', function (Blueprint $table) {
        $table->id();
        $table->foreignId('application_id')->constrained('owner_applications')->onDelete('cascade');      
        $table->string('name'); // e.g., Deluxe King            $table->decimal('base_price', 10, 2); // default price
        $table->integer('capacity'); //this mean how many room and capacity of people , it mean fronend generate room name by capacity
        $table->decimal('default_price', 8, 2);
        $table->text('description')->nullable();
        $table->boolean('is_available')->default(true); // globally available or not
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
