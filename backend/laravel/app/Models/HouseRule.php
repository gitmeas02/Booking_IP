<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseRule extends Model
{
    protected $table = 'house_rule'; // specify table name if not standard plural

    protected $fillable = [
        'application_id',
        'checkin_from',
        'checkout_from',
        'checkout_to',
        'allow_pet',
        'is_childrenAllowed'
    ];

    // Relationship: HouseRule belongs to an OwnerApplication
    public function ownerApplication()
    {
        return $this->belongsTo(OwnerApplication::class, 'application_id');
    }
}
