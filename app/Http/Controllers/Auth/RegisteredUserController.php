<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['required','string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
        ]);

        // Assign the role based on user type
        if ($request->user_type === 'admin') {
            $user->assignRole('admin');
        } elseif ($request->user_type === 'project-manager') {
            $user->assignRole('project-manager');
        } elseif ($request->user_type === 'freelancer-client') {
            $user->assignRole('freelancer-client');
        }

        event(new Registered($user));

        Auth::login($user);

        if($request->user_type == 'admin'){
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }
        else if($request->user_type == 'project-manager'){
            return redirect()->intended(route('manager.dashboard', absolute: false));
        }
        else if($request->user_type == 'freelancer-client'){
            return redirect()->intended(route('dashboard', absolute: false));
        }
    }
}
