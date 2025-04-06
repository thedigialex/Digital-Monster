<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivacyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && Auth::user()->privacy_accept == 1) {
            return $next($request);
        }
        return redirect('/profile/privacy')->with('error', 'You must accept the privacy policy');
    }
}
