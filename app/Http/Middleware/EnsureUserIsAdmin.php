<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class EnsureUserIsAdmin
{
    public function handle($request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect(property_exists($this, 'redirectRoute') ? $this->redirectRoute : '/login');
        }

        if (! Auth::user()->hasRole('admin')) {
            throw new UnauthorizedException('You do not have permission to access the admin panel.');
            // Or redirect: return redirect('/')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}