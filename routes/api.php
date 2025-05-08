<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Route::middleware('api')->group(function () {
    // Your API routes go here
    Route::post('/register', [UserController::class, 'register']);
// });

