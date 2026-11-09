<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('profile.edit', [
            'user' => $user,
            'canDeleteAccount' => $user->role === 'superadmin'
                && User::where('role', 'superadmin')->count() > 1,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $oldPhoto = $user->profile_photo_path;
        $newPhoto = $request->file('profile_photo')?->store('profile-photos', 'public');

        $user->fill(collect($validated)->only(['name', 'email'])->all());

        if ($newPhoto) {
            $user->profile_photo_path = $newPhoto;
        } elseif ($request->boolean('remove_profile_photo')) {
            $user->profile_photo_path = null;
        }

        $emailChanged = $user->isDirty('email');
        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        if (($newPhoto || $request->boolean('remove_profile_photo')) && $oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }

        if ($emailChanged) {
            $user->sendEmailVerificationNotification();
        }

        return Redirect::route('profile.edit')->with('status', $emailChanged ? 'profile-updated-verification-sent' : 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->role === 'admin' || ($user->role === 'superadmin' && User::where('role', 'superadmin')->count() <= 1)) {
            abort(403, 'Akun tidak dapat dihapus melalui halaman profil.');
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
