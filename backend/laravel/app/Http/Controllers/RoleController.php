<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function checkRole($role, Request $request)
    {
        $user = $request->user();

        $hasRole = $user->roles()->where('name', $role)->exists();

        return response()->json([
            'hasRole' => $hasRole,
            'roles' => $user->roles->pluck('name'),
        ]);
    }
    public function switchRole(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'role' => 'required|string|in:user,owner',
        ]);

        $targetRole = $request->role;

        // Only users with 'user' role can switch to 'owner'
        if ($targetRole === 'owner' && !$user->roles()->where('name', 'user')->exists()) {
            return response()->json(['message' => 'Only users can switch to owner.'], 403);
        }

        $role = Role::where('name', $targetRole)->firstOrFail();

        if (!$user->roles->contains($role->id)) {
            $user->roles()->attach($role->id);
        }

        return response()->json([
            'message' => "Role switched to {$role->name}",
            'roles' => $user->roles->pluck('name'),
        ]);
    }


    /**
     * Get the current authenticated user's roles.
     */
    public function getUserRoles(Request $request)
    {
        $user = auth()->user();
        return response()->json([
            'roles' => $user->roles->pluck('name'),
        ]);
    }
}
