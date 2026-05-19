<?php

namespace App\Http\Controllers\BanstechAdmin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::guard('admin')->user();

        return view('admin.profile.edit', [
            'user' => $user,
            'profile' => $user->profile ?? new Profile(['user_id' => $user->id]),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->forceFill([
            'name' => $validated['name'],
        ])->save();

        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        if ($request->hasFile('avatar')) {
            if ($profile->avatar && Storage::disk('public')->exists($profile->avatar)) {
                Storage::disk('public')->delete($profile->avatar);
            }

            $profile->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $profile->save();

        return redirect()->route('admin.profile.edit')->with('status', 'profile-updated');
    }
}
