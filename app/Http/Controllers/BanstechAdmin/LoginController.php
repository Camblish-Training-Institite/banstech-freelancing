<?php

namespace App\Http\Controllers\BanstechAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }


    public function store(LoginRequest $request): RedirectResponse
    {
        // Attempt to authenticate using the 'admin' guard
        if (! Auth::guard('admin')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Check if the authenticated user actually has the admin role from your Spatie roles table
        if (! Auth::guard('admin')->user()->hasRole('admin')) {
            Auth::guard('admin')->logout();
            throw ValidationException::withMessages([
                'email' => 'You do not have administrative access.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/banstech-admin/login');
    }
}
