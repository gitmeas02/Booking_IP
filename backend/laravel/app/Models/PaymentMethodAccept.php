<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodAccept extends Model
{
     protected $fillable = [
        'application_id',
        'at_property',
        'online_payment',
    ];

    public function application()
    {
        return $this->belongsTo(OwnerApplication::class, 'application_id');
    }
}
