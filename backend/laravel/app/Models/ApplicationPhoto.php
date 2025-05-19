<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationPhoto extends Model
{
   protected $fillable = ['application_id', 'url'];

    public function application()
    {
        return $this->belongsTo(OwnerApplication::class, 'application_id');
    }
}
