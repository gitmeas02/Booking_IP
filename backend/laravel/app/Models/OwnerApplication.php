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

       /**
     * The user who submitted the application.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Owner's personal information.
     */
    public function personalInfo() 
    {
        return $this->hasOne(OwnerPersonalInfo::class, 'application_id');
    }

    /**
     * Application location details.
     */
    public function location() 
    {
        return $this->hasOne(ApplicationLocation::class, 'application_id');
    }

    /**
     * Amenities selected in the application.
     */
    public function amenities() 
    {
        return $this->hasMany(ApplicationAmenity::class, 'application_id');
    }

    /**
     * Service options selected (e.g., breakfast, parking).
     */
    public function services() 
    {
        return $this->hasOne(ApplicationService::class, 'application_id');
    }

    /**
     * Photos uploaded for the application.
     */
    public function photos() 
    {
        return $this->hasMany(ApplicationPhoto::class, 'application_id');
    }

    /**
     * House rules defined by the owner.
     */
    public function houseRules() 
    {
        return $this->hasMany(HouseRule::class, 'application_id');
    }

    /**
     * Accepted payment methods.
     */
    public function paymentMethods() 
    {
        return $this->hasOne(PaymentMethodAccept::class, 'application_id');
    }

    /**
     * Room types added for this application.
     */
    public function roomTypes() 
    {
        return $this->hasMany(RoomType::class, 'owner_application_id');
    }

    /**
     * Feedback left by users for this property.
     */
    public function feedbacks() 
    {
        return $this->hasMany(Feedback::class, 'owner_application_id');
    }

    /**
     * Commissions generated for this application.
     */
    public function commissions() 
    {
        return $this->hasMany(Commission::class, 'owner_application_id');
    }



}
