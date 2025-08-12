<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    public function createAdmin(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if(auth()->user()->hasRole('admin')){
            return redirect()->back()->with('log in from admin panel!');
        }
        else if(auth()->user()->hasRole('project-manager')){
            return redirect()->intended(route('pm.dashboard', absolute: false));
        }
        else if(auth()->user()->hasRole('freelancer-client')){
            return redirect()->intended(route('freelancer.dashboard', absolute: false));
        }
        else{
            return redirect()->back()->with('error', 'something went wrong');
        }
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        return redirect('/');
    }
}
