<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Request;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'display_name',
        'email',
        'phone',
        'dob',
        'nationality',
        'gender',
        'address',
        'passport',
        'password',
        'current_role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
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
    public function booking()
    {
        return $this->hasMany(Booking::class);
    }
    // For current role
    public function currentRole()
    {
        return $this->belongsTo(Role::class, 'current_role_id');
    }
    // Get current role name
    //     public function getCurrentRoleName()
    // {
    //     return $this->currentRole ? $this->currentRole->name : null;
    // }

    /**
     * Switch to a specific role.
     */
    public function switchToRole($roleName)
    {
        // Find the role
        $role = Role::where('name', $roleName)->first();
        
        if (!$role) {
            return false;
        }

        // Check if user has this role
        if (!$this->hasRole($roleName)) {
            return false;
        }

        // Update current role
        $this->current_role_id = $role->id;
        return $this->save();
    }

    /**
     * Get the current role name.
     */
    public function getCurrentRoleName()
    {
        if ($this->currentRole) {
            return $this->currentRole->name;
        }

        // Fallback to first available role if no current role is set
        $firstRole = $this->roles()->first();
        if ($firstRole) {
            $this->current_role_id = $firstRole->id;
            $this->save();
            return $firstRole->name;
        }

        return 'user'; // Default role
    }

    /**
     * Get all role names for this user.
     */
    public function getRoleNames()
    {
        return $this->roles->pluck('name')->toArray();
    }

    /**
     * Check if user has multiple roles.
     */
    public function hasMultipleRoles()
    {
        return $this->roles()->count() > 1;
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        
        if ($role && !$this->hasRole($roleName)) {
            $this->roles()->attach($role->id);
            
            // Set as current role if it's the first role
            if (!$this->current_role_id) {
                $this->current_role_id = $role->id;
                $this->save();
            }
            
            return true;
        }
        
        return false;
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        
        if ($role && $this->hasRole($roleName)) {
            $this->roles()->detach($role->id);
            
            // If this was the current role, switch to another role
            if ($this->current_role_id == $role->id) {
                $newRole = $this->roles()->first();
                if ($newRole) {
                    $this->current_role_id = $newRole->id;
                } else {
                    $this->current_role_id = null;
                }
                $this->save();
            }
            
            return true;
        }
        
        return false;
    }
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'dob' => 'date',
    ];
}
