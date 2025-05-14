<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerApplication extends Model
{
    //Register as Admin
    use HasFactory;
    protected $fillable = [
        'user_id',
        'property_type',
        'property_name',
        'description',
        'location',
        'star_rating',
        'is_pet_allowed',
        'status',
        'expires_at',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
}
