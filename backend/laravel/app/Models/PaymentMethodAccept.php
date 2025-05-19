<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodAccept extends Model
{
     protected $fillable = [
        'application_id',
        'credit_card_at_property',
        'online_payment',
        // 'use_platform_payments',
    ];

    public function application()
    {
        return $this->belongsTo(OwnerApplication::class, 'application_id');
    }
}
