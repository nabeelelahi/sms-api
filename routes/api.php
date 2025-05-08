<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::prefix('user')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::middleware(['auth:api'])->group(function () {
        Route::post('/register', [UserController::class, 'register']);
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{role}/role', [UserController::class, 'getByRole']); 
        Route::put('/{id}', [UserController::class, 'update']); 
        Route::put('/{id}/role', [UserController::class, 'updateUserRole']);
    });
});
