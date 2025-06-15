<?php

namespace App\Models\Room;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockedRoom extends Model
{ 
    protected $table='room_blocked_dates';
    protected $fillable=['room_type_id','start_date','end_date','reason'];
    public function rooms(){
       return $this->belongsTo(RoomType::class,'room_type_id');
    }
}