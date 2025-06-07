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
        Schema::create('owner_applications', function (Blueprint $table) {
            $table->id();
            // Foreign key to users
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->string(column: 'property_type');
            $table->string(column: 'property_name');
            $table->text(column: 'description')->nullable();
            
            // Foreign key to application_location
            $table->integer('star_rating')->nullable(); // e.g., 3 stars
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('property_status', ['active', 'inactive'])->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_applications');
    }
};
