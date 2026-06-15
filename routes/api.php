<?php

use App\Http\Controllers\Api\Admin\EmployeeController;
use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/auth/reset-initial-password', [AuthController::class, 'resetInitialPassword'])
        ->middleware('throttle:login_api');

    // api/admin
    Route::prefix('admin')->group(function () {
        Route::post('/employees', [EmployeeController::class, 'store']);
        Route::get('/employees/{user}', [EmployeeController::class, 'show']);
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);
    });
});
