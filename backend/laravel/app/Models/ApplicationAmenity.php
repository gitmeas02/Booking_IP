<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationAmenity extends Model
{
    protected $fillable = ['application_id', 'amenity_id'];

    public function application()
    {
        return $this->belongsTo(OwnerApplication::class, 'application_id');
    }

    public function amenity()
    {
        return $this->belongsTo(Amenity::class, 'amenity_id');
    }
}
