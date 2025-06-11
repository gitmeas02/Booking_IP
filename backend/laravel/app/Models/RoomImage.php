<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomImage extends Model
{
    use HasFactory;

    protected $fillable = ['room_type_id', 'image_url','thumbnail_url'];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
