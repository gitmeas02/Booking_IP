<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
     protected $fillable = [
        'user_id',
        'image',
        'phone',
        'address',
        'gender',
        'date_of_birth',
        'card_holder_name',
        'card_number_last4',
        'brand',
        'expiration_month',
        'expiration_year',
    ];

    // Relationship: Each profile belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
