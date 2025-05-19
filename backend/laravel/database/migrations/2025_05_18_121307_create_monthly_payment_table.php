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
        Schema::create('monthly_payment', function (Blueprint $table) {
            $table->id();

        // Foreign key to hotels (properties)
        $table->unsignedBigInteger('owner_applications_id');
        $table->foreign('owner_applications_id')->references('id')->on('owner_applications')->onDelete('cascade');

        $table->decimal('payment_amount', 10, 2); // amount due for the month

        // The payment date (when payment was made)
        $table->date('payment_date')->nullable(); // nullable in case payment not made yet

        // The month this payment covers - stored as first day of the month for simplicity
        $table->date('payment_month');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_payment');
    }
};
