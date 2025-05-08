<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateUserRoleRequest;
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


    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        // If using Sanctum in future:
        $token = $user->createToken('mobile-app-token')->plainTextToken;
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }
   
    public function getByRole($role)
    {
        $users = User::where('role', $role)->get();
        return response()->json($users);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'age', 'location']));
        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $user
        ]);
    }

    public function updateUserRole(UpdateUserRoleRequest $request, $id)
    {    
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();
        return response()->json([
            'message' => 'User role updated successfully.',
            'user' => $user
        ]);
    }

}
