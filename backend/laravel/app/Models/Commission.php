<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = [
        'owner_application_id',
        'total_commission',
        'month',
        'status',
    ];

    public function application()
    {
        return $this->belongsTo(OwnerApplication::class, 'owner_application_id');
    }
}
