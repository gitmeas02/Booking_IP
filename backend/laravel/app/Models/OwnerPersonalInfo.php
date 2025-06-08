<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OwnerPersonalInfo extends Model
{
    protected $table ='owner_personal_infos';
protected $fillable = [
    'application_id',
    'full_name',
    'email',
    'phone_number',
    'country_region',
    'address_line1',
    'address_line2',
    'id_first_name',
    'id_last_name',
    'id_middle_name',
];


    public function application()
    {
        return $this->belongsTo(OwnerApplication::class, 'application_id');
    }
}
