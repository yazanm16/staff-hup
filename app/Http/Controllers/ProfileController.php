<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Services\ProfileService;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __construct(
        protected ProfileService $profileService
    ) {}

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->profileService->updateProfile(
            $request->user(),
            $request->validated(),
            $request->file('image')
        );

        return redirect()
            ->route('profile.edit')
            ->with('status', 'profile-updated');
    }
}
