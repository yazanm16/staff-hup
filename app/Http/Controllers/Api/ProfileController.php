<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Resources\ProfileResource;
use App\Services\ProfileService;
use App\Traits\ApiResponse;

class ProfileController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ProfileService $profileService
    ) {}

    public function update(ProfileUpdateRequest $request)
    {
        $user = $this->profileService->updateProfile(
            $request->user(),
            $request->validated(),
            $request->file('image')
        );

        return $this->success(
            ['user' => new ProfileResource($user)],
            'Profile updated successfully'
        );
    }
    public function changePassword(UpdatePasswordRequest $request)
    {
        $this->profileService->changePassword(
            $request->user(),
            $request->validated()['password']
        );

        return $this->success(
            [],
            'Password changed successfully'
        );
    }
}
