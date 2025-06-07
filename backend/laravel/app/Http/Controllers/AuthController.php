<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Hash;
use Illuminate\Http\Request;

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

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token'=> $token,
            'roles' => $user->roles->pluck('name'),
        ]);
    }
    public function me(Request $request)
    {     $user = $request->user();
        return response()->json([
            'user' => $user,
            'roles' => $user->roles->pluck('name'),
            'applications' => $user->ownerApplication()->get(),
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
