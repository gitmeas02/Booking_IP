<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = ['owner_application_id', 'name', 'capacity', 'default_price', 'description'];

    public function property()
    {
        return $this->belongsTo(OwnerApplication::class, 'owner_application_id');
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class);
    }

    public function prices()
    {
        return $this->hasMany(RoomPrice::class);
    }}
