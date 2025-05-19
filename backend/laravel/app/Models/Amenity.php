<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = ['amenity_name', 'icon_url'];
    
    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class);
    }
}
