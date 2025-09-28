<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{


    public function edit(): View
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $data = $request->validated();

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture_path) {
                Storage::disk('local')->delete($user->profile_picture_path);
            }

            $data['profile_picture_path'] = $request->file('profile_picture')
                ->store('images/profile', 'local');
        }

        $user->update($data);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    public function uploadCV(Request $request): RedirectResponse
    {
        $request->validate([
            'cv_file' => 'required|file|mimes:pdf|max:10240'
        ]);

        $user = Auth::user();

        if ($user->cv_file_path) {
            Storage::disk('local')->delete($user->cv_file_path);
        }

        $cvPath = $request->file('cv_file')->store('documents/cv', 'local');

        $user->update(['cv_file_path' => $cvPath]);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'CV uploaded successfully.');
    }

    public function deleteCV(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->cv_file_path) {
            Storage::disk('local')->delete($user->cv_file_path);
            $user->update(['cv_file_path' => null]);

            return redirect()->route('admin.profile.edit')
                ->with('success', 'CV deleted successfully.');
        }

        return redirect()->route('admin.profile.edit')
            ->with('error', 'No CV file found to delete.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('admin.profile.edit')
                ->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Password updated successfully.');
    }

    public function updateSocial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'website' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'github' => 'nullable|url|max:255',
            'orcid' => 'nullable|url|max:255',
            'google_scholar' => 'nullable|url|max:255',
            'researchgate' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
        ]);

        $user = Auth::user();
        $user->update($validated);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Social links updated successfully.');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('local')->delete($user->avatar);
        }

        $avatarPath = $request->file('avatar')->store('images/avatars', 'local');

        $user->update(['avatar' => $avatarPath]);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Avatar updated successfully.');
    }

    public function deleteAvatar(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('local')->delete($user->avatar);
            $user->update(['avatar' => null]);

            return redirect()->route('admin.profile.edit')
                ->with('success', 'Avatar deleted successfully.');
        }

        return redirect()->route('admin.profile.edit')
            ->with('error', 'No avatar found to delete.');
    }
}
