<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')
            ->withCount('complaints')
            ->latest()
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with('complaints')->findOrFail($id);

        return view('admin.users.detail', compact('user'));
    }
}