<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    // public function checkRole(Request $request)
    // {
    //     $validated = $request->validate([
    //         'roles' => 'required|array',
    //         'role' => 'required|string|exists:roles,name',
    //     ]);
    //     $roleName = $validated['role'];
    //     $user = $request->user();

    //     $hasRole = $user->roles()->wheres('name', $roleName)->exists();

    //     return response()->json([
    //         'hasRole' => $hasRole,
    //         'roles' => $user->roles->pluck('name'),
    //     ]);
    // }
    /**
     * Get the current authenticated user's roles.
     */
    public function getUserRoles($userId)
    {
        try {
            $user = User::with(['roles', 'currentRole'])->findOrFail($userId);
            
            return response()->json([
                'success' => true,
                'roles' => $user->roles,
                'current_role' => $user->getCurrentRoleName(),
                'current_role_id' => $user->current_role_id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found or error fetching roles',
            ], 404);
        }
    }

    public function switchRole(Request $request)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = $request->user();
        $requestedRole = $request->input('role');
        
        // Check if user has this role
        if (!$user->hasRole($requestedRole)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to switch to this role'
            ], 403);
        }

        // Switch to the requested role
        $success = $user->switchToRole($requestedRole);
        
        if ($success) {
            // Refresh user data
            $user->refresh();
            $user->load(['roles', 'currentRole']);
            
            return response()->json([
                'success' => true,
                'current_role' => $user->getCurrentRoleName(),
                'current_role_id' => $user->current_role_id,
                'message' => 'Role switched successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to switch role'
            ], 400);
        }
    }

    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::findOrFail($request->user_id);
        $role = Role::where('name', $request->role)->first();

        // Check if user already has this role
        if ($user->hasRole($request->role)) {
            return response()->json([
                'success' => false,
                'message' => 'User already has this role'
            ], 400);
        }

        // Assign the role
        $user->roles()->attach($role->id);

        // If this is the user's first role, set it as current
        if ($user->roles()->count() === 1) {
            $user->update(['current_role_id' => $role->id]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role assigned successfully'
        ]);
    }

    public function removeRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::findOrFail($request->user_id);
        $role = Role::where('name', $request->role)->first();

        if (!$user->hasRole($request->role)) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have this role'
            ], 400);
        }

        // Remove the role
        $user->roles()->detach($role->id);

        // If this was the current role, switch to another role or set to null
        if ($user->current_role_id === $role->id) {
            $newCurrentRole = $user->roles()->first();
            $user->update(['current_role_id' => $newCurrentRole ? $newCurrentRole->id : null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role removed successfully'
        ]);
    }
}
