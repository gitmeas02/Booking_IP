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
        Schema::create('owner_personal_infos', function (Blueprint $table) {
           $table->id();
            // Foreign key to owner_applications
            $table->unsignedBigInteger('application_id');
            $table->foreign('application_id')->references('id')->on('owner_applications')->onDelete('cascade');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('email');
            $table->string('phone_number');
            $table->string('country_region');
            $table->string('address_line1');
            $table->string('address_line2')->nullable();

            // ID details
            $table->string('id_first_name');
            $table->string('id_last_name');
            $table->string('id_middle_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_personal_infos');
    }
};
