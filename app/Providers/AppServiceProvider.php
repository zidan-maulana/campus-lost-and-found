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
        // Angka di sidebar harus ikut akun yang login — bukan angka statis dari prototipe.
        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            $user = auth()->user();

            if (! $user) {
                $view->with('navCounts', ['messages' => 0, 'notifications' => 0]);

                return;
            }

            $view->with('navCounts', [
                'messages' => \App\Models\Claim::query()
                    ->where(function ($q) use ($user) {
                        $q->where('claimant_id', $user->id)
                            ->orWhereHas('report', fn ($r) => $r->where('user_id', $user->id));
                    })
                    ->count(),
                'notifications' => \App\Models\AppNotification::query()
                    ->where('user_id', $user->id)
                    ->whereNull('read_at')
                    ->count(),
            ]);
        });

        if (request()->server('HTTP_X_FORWARDED_PROTO') === 'https' || 
            request()->header('x-forwarded-proto') === 'https' ||
            str_contains(request()->header('host', ''), 'loca.lt') ||
            str_contains(request()->header('host', ''), 'ngrok') ||
            str_contains(request()->header('host', ''), 'trycloudflare.com')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
