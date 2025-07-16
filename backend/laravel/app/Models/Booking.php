<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // Explicitly set the table name to match the actual database table
    protected $table = 'booking';
    
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
        'payment_transaction_id',
        'payment_method',
        'status'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(RoomType::class, 'room_id');
    }

    public function ownerApplication()
    {
        return $this->belongsTo(OwnerApplication::class, 'application_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'payment_transaction_id', 'transaction_id');
    }
}
