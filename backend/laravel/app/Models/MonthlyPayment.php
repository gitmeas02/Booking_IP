<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyPayment extends Model
{
  protected $fillable = [
        'application_id',
        'payment_amount',
        'payment_date',
        'payment_month',
    ];

    public function ownerApplication()
    {
        return $this->belongsTo(OwnerApplication::class, 'application_id');
    }
}
