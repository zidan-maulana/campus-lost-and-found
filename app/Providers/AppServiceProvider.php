<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (request()->server('HTTP_X_FORWARDED_PROTO') === 'https' || 
            request()->header('x-forwarded-proto') === 'https' ||
            str_contains(request()->header('host', ''), 'loca.lt') ||
            str_contains(request()->header('host', ''), 'ngrok') ||
            str_contains(request()->header('host', ''), 'trycloudflare.com')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
