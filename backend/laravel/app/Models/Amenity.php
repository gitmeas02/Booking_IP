<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $table='amenities';
    protected $fillable = ['amenity_name', 'group_id'];
    public function ownerApplications()
    {
        return $this->belongsToMany(OwnerApplication::class, 'application_amenities', 'amenity_id', 'application_id');
    }
    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'amenity_room_type', 'amenity_id', 'room_type_id');
    }
     public function group()
    {
        return $this->belongsTo(AmenityGroup::class, 'group_id');
    }
}
