<?php

namespace App\Http\Controllers;

use App\Models\BankDetail;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Skill;
use App\Models\Qualification;
use App\Models\Portfolio;
use App\Models\Certificate;

class ProfileController extends Controller
{

    public function viewFreelancerProfile($freelancerId)
    {
        $freelancer = User::with([
            'profile.skills',
            'qualification',
            'certificate',
            'portfolio',
            'contractsAsFreelancer.job',
            'reviews.job.user.profile',
        ])->findOrFail($freelancerId);
        $profile = $freelancer->profile;

        return view('Users.Clients.layouts.freelancer-profile', compact('freelancer', 'profile'));
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile(); // empty profile if none

        return view('profile.edit', compact('user', 'profile'));
    }

    public function editClient()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        return view('Users.Clients.profile.edit', compact('user', 'profile'));
    }

    public function updateClient(Request $request)
    {
        try {
            $user = Auth::user();
            $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'bio' => 'nullable|string|max:1000',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'zip_code' => 'nullable|string|max:20',
                'state' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'company' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'timezone' => 'nullable|string|max:50',
                'avatar' => 'nullable|image|max:2048',
            ]);

            $profile->fill(collect($validatedData)->except('avatar')->all());
            $profile->user_id = $user->id;

            if ($request->hasFile('avatar')) {
                if ($profile->avatar && Storage::disk('public')->exists($profile->avatar)) {
                    Storage::disk('public')->delete($profile->avatar);
                }

                $profile->avatar = $request->file('avatar')->store('avatars', 'public');
            }

            $profile->save();

            return back()->with('status', 'Profile updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return back()->withErrors($validationException->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating client profile: ' . $e->getMessage());

            return back()->with('error', 'An error occurred while updating your profile. Please try again later.');
        }
    }

    public function updateAddress(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        $request->validate([
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        // Update profile fields
        $profile->address = $request->input('street');
        $profile->city = $request->input('city');
        $profile->zip_code = $request->input('zip_code');
        $profile->state = $request->input('state');
        $profile->country = $request->input('country');
        $profile->save();

        return back()->with('status', 'Address updated successfully.');
    }

    public function updateBankDetails(Request $request)
    {
        try {
            $user = Auth::user();

            $validatedData = $request->validate([
                'account_holder_name' => 'nullable|string|max:255',
                'bank_name' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:50',
                'account_type' => 'nullable|string|max:50',
                'branch_code' => 'nullable|string|max:50',
                'swift_code' => 'nullable|string|max:50',
            ]);

            $hasBankData = collect($validatedData)
                ->filter(fn ($value) => filled($value))
                ->isNotEmpty();

            if (! $hasBankData) {
                $user->bankDetail()?->delete();

                return back()->with('status', 'Bank details cleared successfully.');
            }

            foreach (['account_holder_name', 'bank_name', 'account_number'] as $requiredField) {
                if (! filled($validatedData[$requiredField] ?? null)) {
                    return back()->withErrors([
                        $requiredField => 'This field is required when saving bank details.',
                    ])->withInput();
                }
            }

            BankDetail::updateOrCreate(
                ['user_id' => $user->id],
                $validatedData
            );

            return back()->with('status', 'Bank details updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return back()->withErrors($validationException->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating bank details: ' . $e->getMessage());

            return back()->with('error', 'An error occurred while updating your bank details. Please try again later.');
        }
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

    // public function update(Request $request)
    // {
    //     $user = Auth::user();
    //     // dd($user);
    //     $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
    //     try {
            
    //         // Validate the request data
    //         $validatedData = $request->validate([
    //             'bio'       => 'nullable|string',
    //             'address'   => 'nullable|string|max:255',
    //             'city'      => 'nullable|string|max:255',
    //             'zip_code'  => 'nullable|string|max:20',
    //             'state'     => 'nullable|string|max:255',
    //             'country'   => 'nullable|string|max:255',
    //             'company'   => 'nullable|string|max:255',
    //             'location'  => 'nullable|string|max:255',
    //             'timezone'  => 'nullable|string|max:50',
    //             'avatar'    => 'nullable|image|max:2048', // Max file size: 2MB
    //         ]);

    //         // Update profile fields except for 'avatar'
    //         $profile->fill($request->except('avatar'));

    //         // Handle avatar upload
    //         if ($request->hasFile('avatar')) {
    //             // Delete old avatar if it exists
    //             if ($profile->avatar && Storage::disk('public')->exists($profile->avatar)) {
    //                 Storage::disk('public')->delete($profile->avatar);
    //             }

    //             // Store the new avatar
    //             $avatarPath = $request->file('avatar')->store('avatars', 'public');
    //             $profile->avatar = $avatarPath;
    //         }

    //         // Save the profile
    //         $profile->save();

    //         // Redirect with success message
    //         return redirect()->route('freelancer.dashboard')->with('status', 'Profile updated successfully.');

    //     } catch (\Illuminate\Validation\ValidationException $validationException) {
    //         // Handle validation errors
    //         return back()->withErrors($validationException->errors())->withInput();
    //     } catch (\Exception $e) {
    //         // Handle other unexpected errors
    //         Log::error('Error updating profile: ' . $e->getMessage());
    //         return back()->with('error', 'An error occurred while updating your profile. Please try again later.');
    //     }
    // }

    //Method to update Certifications
    public function updateCertifications(Request $request){
        try {
            $user = Auth::user();
            $validatedData = $request->validate([
                'certificate_name' => 'nullable|string|max:255',
                'issuing_organization' => 'nullable|string|max:255',
                'issue_date' => 'nullable|date',
                'expiration_date' => 'nullable|date|after_or_equal:issue_date',
            ]);

            $hasCertificateData = collect($validatedData)
                ->filter(fn ($value) => filled($value))
                ->isNotEmpty();

            if (! $hasCertificateData) {
                $user->certificate()?->delete();
                return back()->with('status', 'Certificate updated successfully.');
            }

            Certificate::updateOrCreate(
                ['user_id' => $user->id],
                $validatedData
            );

            return back()->with('status', 'Certificate updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return back()->withErrors($validationException->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating certifications: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating your certificate. Please try again later.');
        }
    }

    //Method to update Qualifications
    public function updateQualifications(Request $request){
        // dd($request);
        try {
            $user = Auth::user();
            $qualification = $user->qualification ?? new Qualification(['user_id' => $user->id]);
            $validatedData = $request->validate([
                'degree' => 'required|string|max:255',
                'institution' => 'required|string|max:255',
                'year_of_completion' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            ]);

            $qualification->fill($validatedData);
            $qualification->save();

            return back()->with('status', 'Qualifications updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            Log::error('Error updating qualifications: ' . $validationException->getMessage());
            return back()->withErrors($validationException->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating qualifications: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating your qualifications. Please try again later.');
        }
    }

    // Method to update the skills
    public function updateSkills(Request $request)
    {
        try {
            $user = Auth::user();
            $profile = $user->profile ?? Profile::create(['user_id' => $user->id]);

            $validated = $request->validate([
                'skills' => 'nullable|string',
            ]);

            $rawSkills = $validated['skills'] ?? '[]';
            $decodedSkills = json_decode($rawSkills, true);
            $skillNames = is_array($decodedSkills)
                ? $decodedSkills
                : preg_split('/\s*,\s*/', $rawSkills, -1, PREG_SPLIT_NO_EMPTY);

            $skillIds = collect($skillNames)
                ->filter(fn ($name) => filled(trim((string) $name)))
                ->map(fn ($name) => Skill::firstOrCreate(['name' => trim((string) $name)])->id)
                ->unique()
                ->values()
                ->all();

            $profile->skills()->sync($skillIds);

            return back()->with('status', 'Skills updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return back()->withErrors($validationException->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("Error updating skills: " . $e->getMessage());
            return back()->with('error', 'An error occurred while updating your skills. Please try again later.');
        }
    }

    // Method to update About me section
    public function updateAboutMe(Request $request){
        try {
            $user = Auth::user();
            $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

            // Validate the request data
            $validatedData = $request->validate([
                'bio' => 'nullable|string|max:1000',
            ]);

            // Update profile fields
            $profile->bio = $validatedData['bio'];
            $profile->save();

            return back()->with('status', 'About Me section updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            // Handle validation errors
            return response()->json(['errors' => $validationException->errors()], 422);
        } catch (\Exception $e) {
            // Handle other unexpected errors
            Log::error('Error updating About Me section: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating your About Me section. Please try again later.'], 500);
        }
    }

    public function updateProfile(Request $request)
{
    try {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'title'      => 'nullable|string|max:255',
            'location'   => 'nullable|string|max:255',
        ]);

        // Update Profile (not User)
        $profile->first_name = $validated['first_name'];
        $profile->last_name  = $validated['last_name'];
        $profile->title      = $validated['title'];
        $profile->location   = $validated['location'];

        $profile->save();

        return back()->with('status', 'Profile updated successfully.');

    } catch (\Illuminate\Validation\ValidationException $validationException) {
        return response()->json(['errors' => $validationException->errors()], 422);
    } catch (\Exception $e) {
        Log::error('Error updating profile: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred.'], 500);
    }
}


    // Separate method to handle avatar update
    public function updateAvatar(Request $request){
        try{
            $request->validate([
                'avatar' => 'required|image|max:2048', // Max file size: 2MB
            ]);

            $user = Auth::user();
            $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

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

            return back()->with('status', 'Avatar updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            // Handle validation errors
            return response()->json(['errors' => $validationException->errors()], 422);
        } catch (\Exception $e) {
            // Handle other unexpected errors
            Log::error('Error updating avatar: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating your avatar. Please try again later.'], 500);
        }

    }

    //Update the certificate field in profile table
    public function updateCertificate(Request $request){
        try {
            $user = Auth::user();
            $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

            // Validate the request data
            $validatedData = $request->validate([
                'certificate' => 'nullable|string|max:255',
            ]);

            // Update profile fields
            $profile->certificate = $validatedData['certificate'] ?? $profile->certificate;
            $profile->save();

            return back()->with('status', 'Address updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            // Handle validation errors
            return response()->json(['errors' => $validationException->errors()], 422);
        } catch (\Exception $e) {
            // Handle other unexpected errors
            Log::error('Error updating certificate: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating your certificate. Please try again later.'], 500);
        }
    }

    //update the Edoucation field in profile table
    public function updateEducation(Request $request){
        try {
            $user = Auth::user();
            $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

            // Validate the request data
            $validatedData = $request->validate([
                'education' => 'nullable|string|max:255',
            ]);

            // Update profile fields
            $profile->education = $validatedData['education'] ?? $profile->education;
            $profile->save();

            return response()->json(['message' => 'Education updated successfully.'], 200);

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            // Handle validation errors
            return response()->json(['errors' => $validationException->errors()], 422);
        } catch (\Exception $e) {
            // Handle other unexpected errors
            Log::error('Error updating education: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating your education. Please try again later.'], 500);
        }
    }

    public function storePortfolio(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'imageURL' => 'nullable|url',
            'file_url' => 'nullable|url',
        ]);

        Portfolio::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'imageURL' => $request->imageURL,
            'file_url' => $request->file_url,
        ]);

        return redirect()->back()->with('success', 'Portfolio item added successfully!');
    }
}
