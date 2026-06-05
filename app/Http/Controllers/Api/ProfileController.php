<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
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
    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $user->update($request->validated());

        return ResponseHelper::success($user, 'Profile updated');
    }
}