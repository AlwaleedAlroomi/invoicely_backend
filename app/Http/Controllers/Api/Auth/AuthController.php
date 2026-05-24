<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Resources\User\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    public function register(RegisterUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'current_team_id' => $validated['current_team_id'],
            'branch_id' => $validated['branch_id'] ?? null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $authData = [
            'user' => $user,
            'token'      => $token,
            'token_type' => 'Bearer'
        ];

        $transformedData = new AuthResource((object) $authData);

        return $this->sendResponse($transformedData, 'Account Created successfully', 201);
    }

    public function login(LoginUserRequest $request) {}

    public function logout(Request $request) {}

    public function user(Request $request) {}
}
