<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmenityGroup extends Model
{  
    protected $table = 'amenity_groups';
    protected $fillable = ['name'];

    public function amenities()
    {
        return $this->hasMany(Amenity::class, 'group_id');
    }
}
