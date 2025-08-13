<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile(); // empty profile if none

        return view('profile.edit', compact('user', 'profile'));
    }

    // public function update(Request $request)
    // {
    //     $user = Auth::user();
    //     // dd($user);
    //     $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

    //     // dd($request);

    //     $request->validate([
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'bio' => 'nullable|string|max:1000',
    //         'address' => 'nullable|string|max:255',
    //         'city' => 'nullable|string|max:255',
    //         'zip_code' => 'nullable|string|max:20',
    //         'state' => 'nullable|string|max:255',
    //         'country' => 'nullable|string|max:255',
    //         'company' => 'nullable|string|max:255',
    //         'location' => 'nullable|string|max:255',
    //         'timezone' => 'nullable|string|max:50',
    //         'avatar' => 'nullable|image|max:2048',
    //     ]);

    //     // Update profile fields
    //     $profile->fill($request->except('avatar'));
    //     dd($profile);

    //     // Handle avatar upload
    //     if ($request->hasFile('avatar')) {
    //         // Delete old avatar
    //         if ($profile->avatar && Storage::disk('public')->exists($profile->avatar)) {
    //             Storage::disk('public')->delete($profile->avatar);
    //         }

    //         $profile->avatar = $request->file('avatar')->store('avatars', 'public');
    //     }

    //     $profile->save();

    //     return redirect()->route('dashboard')->with('status', 'Profile updated successfully.');
    // }

    public function update(Request $request)
    {
        $user = Auth::user();
        // dd($user);
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        try {
            
            // Validate the request data
            $validatedData = $request->validate([
                'bio'       => 'nullable|string|max:1000',
                'address'   => 'nullable|string|max:255',
                'city'      => 'nullable|string|max:255',
                'zip_code'  => 'nullable|string|max:20',
                'state'     => 'nullable|string|max:255',
                'country'   => 'nullable|string|max:255',
                'company'   => 'nullable|string|max:255',
                'location'  => 'nullable|string|max:255',
                'timezone'  => 'nullable|string|max:50',
                'avatar'    => 'nullable|image|max:2048', // Max file size: 2MB
            ]);

            // Update profile fields except for 'avatar'
            $profile->fill($request->except('avatar'));

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if it exists
                if ($profile->avatar && Storage::disk('public')->exists($profile->avatar)) {
                    Storage::disk('public')->delete($profile->avatar);
                }

                // Store the new avatar
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $profile->avatar = $avatarPath;
            }

            // Save the profile
            $profile->save();

            // Redirect with success message
            return redirect()->route('freelancer.dashboard')->with('status', 'Profile updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            // Handle validation errors
            return back()->withErrors($validationException->errors())->withInput();
        } catch (\Exception $e) {
            // Handle other unexpected errors
            Log::error('Error updating profile: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating your profile. Please try again later.');
        }
    }
}