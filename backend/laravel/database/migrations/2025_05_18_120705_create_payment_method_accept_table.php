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
        Schema::create('payment_method_accepts', function (Blueprint $table) {
            $table->id();
            // Foreign key to owner_applications
            $table->unsignedBigInteger('application_id');
            $table->foreign('application_id')->references('id')->on('owner_applications')->onDelete('cascade');

            // Boolean flags for payment methods
            $table->boolean('at_property')->default(false);
            $table->boolean('online_payment')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_method_accepts');
    }
};
