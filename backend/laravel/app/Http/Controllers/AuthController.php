<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        if (!Role::where('name', 'user')->exists()) {
        return response()->json([
            'message' => 'Registration is currently unavailable. Please try again later.'
        ], 503); // 503 Service Unavailable
        }
        if (Role::count() === 0) {
        return response()->json([
            'message' => 'Registration is currently unavailable. Please try again later.'
        ], 503);
          }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',//confirmed
        ]);
    
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // $userRole = Role::where('name', 'company')->firstOrFail();
        $userRole = Role::where('name', 'user')->firstOrFail();
        $user->roles()->attach($userRole->id);
        $user->current_role_id = $userRole->id;
        $user->save();


        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials provided.'],
            ]);
        }

        // Load relationships to reduce subsequent API calls
        $user->load(['roles', 'currentRole', 'ownerApplication']);

        // Get role information
        $roles = $user->roles->pluck('name')->toArray();
        $currentRoleName = $user->getCurrentRoleName();
        
        \Log::info('Login response data', [
            'user_id' => $user->id,
            'current_role_id' => $user->current_role_id,
            'current_role_name' => $currentRoleName,
            'roles' => $roles,
            'currentRole_loaded' => $user->relationLoaded('currentRole'),
            'currentRole' => $user->currentRole ? $user->currentRole->name : null,
        ]);
        
        // Create token with role abilities
        $token = $user->createToken('token', $roles)->plainTextToken;

        return response()->json([
            'user'  => [
                'id' => $user->id,
                'name' => $user->name,
                'display_name' => $user->display_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'dob' => $user->dob,
                'nationality' => $user->nationality,
                'gender' => $user->gender,
                'address' => $user->address,
                'passport' => $user->passport,
                'current_role_id' => $user->current_role_id,
                'roles' => $roles,
                'current_role' => $currentRoleName,
                'applications' => $user->ownerApplication,
            ],
            'token'=> $token,
        ]);
    }
    public function me(Request $request)
    {     
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $user->load(['roles', 'currentRole', 'ownerApplication']);
        $roles = $user->roles->pluck('name')->toArray();
        $currentRoleName = $user->getCurrentRoleName();
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'display_name' => $user->display_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'dob' => $user->dob,
                'nationality' => $user->nationality,
                'gender' => $user->gender,
                'address' => $user->address,
                'passport' => $user->passport,
                'current_role_id' => $user->current_role_id,
                'roles' => $roles,
                'current_role' => $currentRoleName,
                'applications' => $user->ownerApplication,
            ],
        ]);
    }

    public function updateMe(Request $request)
{
    $user = $request->user();

    $validated = $request->validate([
        'name' => 'sometimes|string|max:255',
        'display_name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $user->id,
        'phone' => 'sometimes|string|max:20',
        'dob' => 'sometimes|date',
        'nationality' => 'sometimes|string|max:100',
        'gender' => 'sometimes|string|max:20',
        'address' => 'sometimes|string|max:255',
        'passport' => 'sometimes|string|max:50',
    ]);

    $user->update($validated);

    return response()->json([
        'message' => 'Profile updated successfully',
        'user' => $user,
    ]);
}

}
