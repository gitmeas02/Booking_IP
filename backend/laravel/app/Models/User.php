<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable , HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class,'role_user');
    }
    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }
    public function ownerApplication()
    {
        return $this->hasMany(OwnerApplication::class);
    }
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function booking(){
        return $this->hasMany(Booking::class);
    }


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
