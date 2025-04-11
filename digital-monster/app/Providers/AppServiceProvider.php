<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $email = Str::lower((string) $request->input('email'));
            return Limit::perDay(10)->by($email . '|' . $request->ip());
        });
    }
}
