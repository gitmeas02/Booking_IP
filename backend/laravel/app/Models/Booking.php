<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
   protected $fillable = [
        'user_id',
        'room_id',
        'owner_application_id',
        'user_commission',
        'hotel_commission',
        'check_in_date',
        'check_out_date',
        'number_of_guests',
        'total_price',
        'special_request',
        'booking_at',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_id');
    }

    public function ownerApplication()
    {
        return $this->belongsTo(OwnerApplication::class, 'owner_application_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
