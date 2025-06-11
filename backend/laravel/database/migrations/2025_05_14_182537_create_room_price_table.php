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
        Schema::create('room_price', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained('room_type')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->double('percentage');
            $table->integer('custom_price'); // price when 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_price');
    }
};
