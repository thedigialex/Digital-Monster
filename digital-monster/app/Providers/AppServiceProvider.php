<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Middleware\TrustProxies;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
        // Add trusted proxies and headers from environment
        $trustedProxies = env('TRUSTED_PROXIES', '127.0.0.1');

        // Set trusted proxies dynamically
        TrustProxies::at($trustedProxies);

        // Set up rate limiter for login requests
        RateLimiter::for('login', function (Request $request) {
            $email = Str::lower((string) $request->input('email'));
            return Limit::perDay(10)->by($email . '|' . $request->ip());
        });
        
    }
}
