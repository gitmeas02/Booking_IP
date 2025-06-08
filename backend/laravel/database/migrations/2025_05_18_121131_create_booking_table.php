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
          Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');  
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('owner_application_id'); 
            $table->decimal('user_commission', 10, 2); 
            $table->decimal('hotel_commission', 10, 2); 
            $table->date('check_in_date');  
            $table->date('check_out_date'); 
            $table->integer('number_of_guests')->default(1);
            $table->decimal('total_price', 10, 2);   
            $table->text('special_request')->nullable(); 
            $table->timestamp('booking_at')->useCurrent();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('room_types')->onDelete('cascade');
            $table->foreign('owner_application_id')->references('id')->on('owner_applications')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
