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
        Schema::create('application_services', function (Blueprint $table) {
            $table->id();
             // Foreign key to owner_applications
            $table->unsignedBigInteger('application_id');
            $table->foreign('application_id')->references('id')->on('owner_applications')->onDelete('cascade');

            // Optional service offerings
            $table->boolean('breakfast')->default(false);
            $table->boolean('parking')->default(false);
            $table->boolean('allow_pet')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_services');
    }
};
