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
        'star_rating',
        'is_pet_allowed',
        'status',
        'expires_at',
    ];
public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function personalInfo() 
    {
        return $this->hasOne(OwnerPersonalInfo::class, 'application_id');
    }

    public function location() 
    {
        return $this->hasOne(ApplicationLocation::class, 'application_id');
    }

    public function amenities() 
    {
        return $this->belongsToMany(Amenity::class, 'application_amenities', 'application_id', 'amenity_id');
    }

    public function services() 
    {
        return $this->hasOne(ApplicationService::class, 'application_id');
    }

    public function photos() 
    {
        return $this->hasMany(ApplicationPhoto::class, 'application_id');
    }

    public function houseRules() 
    {
        return $this->hasMany(HouseRule::class, 'application_id');
    }

    public function paymentMethods() 
    {
        return $this->hasOne(PaymentMethodAccept::class, 'application_id');
    }

    public function roomTypes() 
    {
        return $this->hasMany(RoomType::class, 'owner_application_id');
    }

    public function feedbacks() 
    {
        return $this->hasMany(Feedback::class, 'owner_application_id');
    }

    public function commissions() 
    {
        return $this->hasMany(Commission::class, 'owner_application_id');
    }
}
