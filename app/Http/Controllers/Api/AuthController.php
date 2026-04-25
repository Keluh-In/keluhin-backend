<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ResponseHelper;

class AuthController extends Controller
{
    /**
     * REGISTER
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user'
        ]);

        return ResponseHelper::success($user, 'Register berhasil');
    }

    /**
     * LOGIN
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return ResponseHelper::error('Email atau password salah', 401);
        }

        $token = $user->createToken('keluhin-token')->plainTextToken;

        return ResponseHelper::success([
            'user' => $user,
            'token' => $token
        ], 'Login berhasil');
    }

    /**
     * LOGOUT
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return ResponseHelper::success(null, 'Logout berhasil');
    }
}