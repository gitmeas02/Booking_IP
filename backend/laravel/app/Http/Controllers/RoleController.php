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
    $user = $request->user();

    // Validate role
    $request->validate([
        'role' => 'required|string|in:user,owner',
    ]);

    $role = Role::where('name', $request->role)->firstOrFail();

    // Attach or detach the role from the user
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
    $user = $request->user();
    return response()->json([
        'roles' => $user->roles->pluck('name'),
    ]);
}
}
