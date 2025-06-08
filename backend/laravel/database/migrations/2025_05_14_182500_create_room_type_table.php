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
        $table->unsignedBigInteger('application_id'); // FK to track ownership
        $table->string('name'); // e.g., Deluxe King            $table->decimal('base_price', 10, 2); // default price
        $table->integer('max_guest');
        $table->text('description')->nullable();
        $table->boolean('is_available')->default(true); // globally available or not
        $table->timestamps();

        $table->foreign('application_id')->references('id')->on('owner_applications')->onDelete('cascade');
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
