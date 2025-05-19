<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'amount',
        'payment_status',
        'payment_method',
        'payment_transaction_id',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
