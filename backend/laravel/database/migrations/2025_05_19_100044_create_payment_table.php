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
        Schema::create('payment', function (Blueprint $table) {
           $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'at_property'])->default('pending'); // 'at_property' for pay at property
            $table->enum('payment_method', ['credit_card', 'paypal','qrcode', 'stripe', 'pay_at_property']); // Added 'pay_at_property' option
            $table->string('payment_transaction_id')->nullable(); // For online payments (e.g., Stripe/PayPal transaction ID)
            $table->timestamps();

            // Foreign key to bookings
            $table->foreign('booking_id')->references('id')->on('booking')->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
