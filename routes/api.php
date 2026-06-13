<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public auth routes
Route::post('/user-register', [AuthController::class, 'userRegister']);
Route::post('/user-login', [AuthController::class, 'userLogin']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout-user', [AuthController::class, 'logout']);

    // Todo CRUD
    Route::get('todos', [TodoController::class, 'index']);
    Route::post('/store-todo', [TodoController::class, 'store']);
    Route::get('todos/{id}', [TodoController::class, 'show']);
    Route::put('todos/{id}', [TodoController::class, 'update']);
    Route::delete('todos/{id}', [TodoController::class, 'destroy']);
    Route::patch('todos/{id}/complete', [TodoController::class, 'markComplete']);
    Route::patch('todos/{id}/pending', [TodoController::class, 'markPending']);

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('notifications/{id}/read', [NotificationController::class, 'markRead']);
});
