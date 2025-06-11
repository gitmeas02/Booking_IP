<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmenityRoomType extends Model
{
    use HasFactory;
    protected $table ='amenities_room_type';
    protected $fillable=['room_id','amenity_id'];
     public function room()
    {
        return $this->belongsTo(RoomType::class, 'room_id');
    }

    public function amenity()
    {
        return $this->belongsTo(Amenity::class, 'amenity_id');
    }
}
