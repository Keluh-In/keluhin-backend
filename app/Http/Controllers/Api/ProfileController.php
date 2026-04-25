<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;

class ProfileController extends Controller
{
    /**
     * GET PROFILE
     */
    public function show(Request $request)
    {
        return ResponseHelper::success($request->user());
    }

    /**
     * UPDATE PROFILE
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
        ]);

        return ResponseHelper::success($user, 'Profile updated');
    }
}