<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class UserController extends Controller
{
    use AuthorizesRequests;

    public function register(RegisterRequest $request)
    {

        $this->authorize('create', arguments: $request->user());

        $payload = $request->validated();

        $record = User::create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'age' => $payload['age'],
            'residence' => $payload['residence'],
            'password' => Hash::make($payload['password']),
            'role' => User::ROLE_STUDENT,
        ]);

        return response()->json([
            'message' => 'Registration successful!',
            'data' => $record
        ], 201);
    }


    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $token = $user->createToken('access-token')->plainTextToken;
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'data' => $user,
        ]);
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', arguments: $request->user());
        $users = User::all();
        return response()->json($users);
    }

    public function getByRole(Request $request, $role)
    {
        $this->authorize('viewByRole', arguments: $request->user());
        $users = User::where('role', $role)->get();
        return response()->json($users);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', arguments: [$request->user(), $user]);
        $user->update($request->only(['name', 'age', 'location']));
        return response()->json([
            'message' => 'User updated successfully.',
            'data' => $user
        ]);
    }

    public function updateUserRole(UpdateUserRoleRequest $request, $id)
    {
        $this->authorize('assignRoles', arguments: $request->user());
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();
        return response()->json([
            'message' => 'User role updated successfully.',
            'data' => $user
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize('delete', arguments: $request->user());
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'message' => 'User role deleted successfully.',
        ]);
    }

    public function logout(Request $request)
    {
        // Revoke the current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

}
