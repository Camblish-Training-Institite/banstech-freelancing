<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            // Check if user exists by email
            $existingUser = User::where('email', $user->getEmail())->first();

            if ($existingUser) {
                // Log in the user
                Auth::login($existingUser, true);
                return redirect()->intended('/dashboard');
            } else {
                // Create a new user
                $newUser = User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'google_id' => $user->getId(),
                    'password' => bcrypt(rand(100000, 999999)),
                ]);

                Auth::login($newUser, true);
                return redirect()->intended('/dashboard');
            }
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Something went wrong with Google login.');
        }
    }
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();

            // Check if user exists by email
            $existingUser = User::where('email', $user->getEmail())->first();

            if ($existingUser) {
                // Log in the user
                Auth::login($existingUser, true);
                return redirect()->intended('/dashboard');
            } else {
                // Create a new user
                $newUser = User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'facebook_id' => $user->getId(),
                    'password' => bcrypt(rand(100000, 999999)),
                ]);

                Auth::login($newUser, true);
                return redirect()->intended('/dashboard');
            }
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Something went wrong with Facebook login.');
        }
    }
    
   


}
