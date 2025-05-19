<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'user_id',
        'owner_application_id',
        'feedback',
        'rating',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ownerApplication()
    {
        return $this->belongsTo(OwnerApplication::class);
    }
}
