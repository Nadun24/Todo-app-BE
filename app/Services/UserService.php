<?php

use App\Models\User;

class UserService
{

    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }
}
