<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];
    public function users()
    {
        return $this->belongsToMany(User::class,'role_user')->withTimestamps();
    }
    public function currentUsers()
    {
        return $this->hasMany(User::class, 'current_role_id');
    }
}
