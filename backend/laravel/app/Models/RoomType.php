<?php

namespace App\Models;

use App\Models\Room\BlockedRoom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;
    protected $table ="room_types";
    protected $fillable = ['application_id', 'name', 'capacity', 'default_price', 'description'];

    public function property()
    {
        return $this->belongsTo(OwnerApplication::class, 'application_id');
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class,'amenities_room_type', 'room_id', 'amenity_id');
    }
    public function blockDates(){
        return $this->hasMany(BlockedRoom::class,'room_type_id');
    }

    public function prices()
    {
        return $this->hasMany(RoomPrice::class);
    }}
