<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    // user registration
    public function userRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required|string|min:8',
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'status' => true,
            'data' => $user
        ]);
    }



    // user login method
    public function userLogin(Request $request)
    {
        // validate request
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password',
                'status' => false,
            ], 401);
        }

        $accessToken = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully',
            'status' => true,
            'data' => [
                'user' => $user,
                'access_token' => $accessToken,
            ],
        ]);
    }

    // logout method
    public function logout()
    {
        $user = auth()->user();

        // remove access token
        $user->currentAccessToken()->delete();

        return response()->json([
            "message" => "User logged out successfully",
            "status" => true,
        ]);
    }
}
