<?php

use App\Exceptions\BusinessRuleException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'active' => App\Http\Middleware\EnsureUserIsActive::class,
            'admin' => App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);

        $middleware->web(append: [
            App\Http\Middleware\EnsureUserIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Pelanggaran aturan bisnis dikembalikan sebagai pesan yang aman dibaca pengguna.
        $exceptions->render(function (BusinessRuleException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => $e->userMessage,
                    'errors' => ['business' => [$e->userMessage]],
                ], $e->statusCode);
            }

            return back()->withInput()->withErrors(['business' => $e->userMessage]);
        });
    })->create();
