<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// register user
Route::post('/register-user', [AuthController::class, 'userRegister']);

// login user
Route::post('/login-user', [AuthController::class, 'userLogin']);

// logout user
Route::post('/logout-user', [AuthController::class, 'logout'])->middleware('auth:sanctum');
