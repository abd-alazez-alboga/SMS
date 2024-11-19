<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        // validate fields
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'phone_number' => 'required|string|regex:/^[0-9]{10,15}$/|unique:users,phone_number',
            'location' => 'required|string|max:255',
            'password' => 'required|min:8|confirmed',
        ]);

        // create user
        $user = User::create([
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'],
            'location' => $validated['location'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        // return user & token in response
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken,
            ],
            'message' => 'User registered successfully',
        ], 201);
    }
    // Login user
    public function login(Request $request)
    {
        // Validate fields
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        // Retrieve the user by phone number
        $user = User::where('phone_number', $validated['phone_number'])->first();

        // Check if user exists and the password is correct
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Create a token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return user & token in response
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => 'Login successful',
        ], 200);
    }

    // Logout user
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
    public function user()
    {
        return response([
            'user' => auth()->user()
        ], 200);
    }
}


/*
 public function register(Request $request)
    {
        // validate fields
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'phone_number' => 'required|string|regex:/^[0-9]{10,15}$/|unique:users,phone_number',
            'location' => 'required|string|max:255',
            'password' => 'required|min:8|confirmed',
        ]);

        // create user
        $user = User::create([
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'],
            'location' => $validated['location'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        // return user & token in response
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken,
            ],
            'message' => 'User registered successfully',
        ], 201);
    }
*/
