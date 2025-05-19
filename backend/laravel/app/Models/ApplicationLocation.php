<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationLocation extends Model
{
   protected $fillable = [
        'owner_application_id',
        'apartment_floor',
        'country',
        'city',
        'postcode',
    ];

    public function application()
    {
        return $this->belongsTo(OwnerApplication::class, 'owner_application_id');
    }
}
