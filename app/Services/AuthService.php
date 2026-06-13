<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function userLogin(array $data)
    {
        $user = $this->userService->getUserByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return false;
        }

        $accessToken = $user->createToken('authToken')->plainTextToken;

        return [
            'user'         => $user,
            'access_token' => $accessToken,
        ];
    }

    public function userCreate(array $data) {}
}
