<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * FUNGSI REGISTER
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user' // Default role sebagai Mahasiswa/User biasa
        ]);

        // Membuat token menggunakan Laravel Sanctum
        $token = $user->createToken('keluhin-token')->plainTextToken;

        // Mengembalikan struktur JSON sukses (Dibungkus dalam array 'data')
        return ResponseHelper::success([
            'user' => $user,
            'token' => $token
        ], 'Register berhasil');
    }

    /**
     * FUNGSI LOGIN
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        // Cari user berdasarkan email
        $user = User::where('email', $data['email'])->first();

        // Validasi kecocokan password
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return ResponseHelper::error('Email atau password salah', 401);
        }

        // Membuat token baru
        $token = $user->createToken('keluhin-token')->plainTextToken;

        // Response dikembalikan dengan struktur yang sama dengan register
        return ResponseHelper::success([
            'user' => $user,
            'token' => $token
        ], 'Login berhasil');
    }
}
