<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationService extends Model
{
    protected $fillable = [
        'application_id',
        'breakfast',
        'parking',
        'allow_pet',
    ];

    public function application()
    {
        return $this->belongsTo(OwnerApplication::class, 'application_id');
    }
}
