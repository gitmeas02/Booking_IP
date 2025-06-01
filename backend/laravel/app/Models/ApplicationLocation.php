<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationLocation extends Model
{
   protected $fillable = [
        'application_id',
        'street',
        'apartment_floor',
        'country',
        'city',
        'postcode',
    ];

    public function ownerApplication()
    {
        return $this->belongsTo(OwnerApplication::class, 'application_id');
    }
}
