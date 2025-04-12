<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PolicyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && Auth::user()->policy_accept == 1) {
            return $next($request);
        }
        return redirect('/profile/policy')->with('error', 'You must accept the policy');
    }
}
