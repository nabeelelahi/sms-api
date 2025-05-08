<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $payload = $request->validated();

        $record = User::create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => Hash::make($payload['password']),
            'role' => User::ROLE_STUDENT,
        ]);

        return response()->json([
            'message' => 'Registration successful!',
            'user' => $record
        ], 201);
    }
}
