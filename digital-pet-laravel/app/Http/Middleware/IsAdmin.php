<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            // Redirect to login if not logged in, or main page if not an admin
            return redirect('/login')->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
