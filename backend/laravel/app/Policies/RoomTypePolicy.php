<?php

namespace App\Policies;

use App\Models\RoomType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RoomTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
public function block(User $user, RoomType $roomType)
{

    if (!$roomType->property) {
        return false;
    }

    return $user->id === $roomType->property->user_id;
}


    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RoomType $roomType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RoomType $roomType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RoomType $roomType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RoomType $roomType): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RoomType $roomType): bool
    {
        return false;
    }
}
