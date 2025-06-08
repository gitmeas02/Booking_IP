<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationService extends Model
{
    protected $table='application_services';
    protected $fillable = [
        'application_id',
        'breakfast',
        'parking',
        'allow_pet',
        'is_childrenAllowed'
    ];

    public function application()
    {
        return $this->belongsTo(OwnerApplication::class, 'application_id');
    }
}
