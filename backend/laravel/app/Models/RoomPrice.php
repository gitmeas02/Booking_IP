<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPrice extends Model
{
    use HasFactory;

    protected $fillable = ['room_type_id', 'start_date', 'end_date', 'custom_price'];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
