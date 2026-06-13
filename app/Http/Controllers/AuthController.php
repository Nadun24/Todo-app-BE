<?php

namespace App\Http\Controllers;

use App\Models\User;
use AuthService;
use ErrorLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use ResponseHelper;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function userRegister(Request $request)
    {
        try {
            $request->validate([
                'name'                  => 'required|string|max:50',
                'email'                 => 'required|email|unique:users,email',
                'password'              => 'required|string|confirmed|min:8',
                'password_confirmation' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return ResponseHelper::success($user, 'User registered successfully', 201);
        } catch (\Exception $e) {
            ErrorLogger::logError($e);
            return ResponseHelper::error(null, 'Registration failed', 500);
        }
    }

    public function userLogin(Request $request)
    {
        try {
            $reqData = $request->validate([
                'email'    => 'required|email|exists:users,email',
                'password' => 'required|string|min:8',
            ]);

            $userData = $this->authService->userLogin($reqData);

            if (!$userData) {
                return ResponseHelper::error(null, 'Invalid email or password', 401);
            }

            return ResponseHelper::success($userData, 'User logged in successfully');
        } catch (\Exception $e) {
            ErrorLogger::logError($e);
            return ResponseHelper::error(null, 'Login failed', 500);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->currentAccessToken()->delete();

            return ResponseHelper::success(null, 'User logged out successfully');
        } catch (\Exception $e) {
            ErrorLogger::logError($e);
            return ResponseHelper::error(null, 'Logout failed', 500);
        }
    }
}
