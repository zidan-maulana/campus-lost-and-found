<?php

use App\Exceptions\BusinessRuleException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
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

        // API v1 memakai autentikasi session cookie (Sanctum belum dipasang),
        // sehingga grup `api` perlu middleware session + CSRF agar Auth::attempt
        // dan X-CSRF-TOKEN (kontrak §2.1) bekerja.
        $middleware->api(prepend: [
            Illuminate\Cookie\Middleware\EncryptCookies::class,
            Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            Illuminate\Session\Middleware\StartSession::class,
            Illuminate\View\Middleware\ShareErrorsFromSession::class,
            Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Envelope error API v1 — docs/API_CONTRACT.md §2.4.
        $envelope = static function (string $message, array $errors, int $code) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => (object) $errors,
                'code' => $code,
            ], $code);
        };

        // Pelanggaran aturan bisnis dikembalikan sebagai pesan yang aman dibaca pengguna.
        $exceptions->render(function (BusinessRuleException $e, Request $request) use ($envelope) {
            if ($request->is('api/v1/*')) {
                return $envelope($e->userMessage, ['business' => [$e->userMessage]], $e->statusCode);
            }

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => $e->userMessage,
                    'errors' => ['business' => [$e->userMessage]],
                ], $e->statusCode);
            }

            return back()->withInput()->withErrors(['business' => $e->userMessage]);
        });

        $exceptions->render(function (ValidationException $e, Request $request) use ($envelope) {
            if ($request->is('api/v1/*')) {
                return $envelope(
                    'Validasi formulir gagal. Silakan periksa kembali data Anda.',
                    $e->errors(),
                    422,
                );
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) use ($envelope) {
            if ($request->is('api/v1/*')) {
                return $envelope('Sesi Anda telah berakhir. Silakan login kembali.', [], 401);
            }
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) use ($envelope) {
            if ($request->is('api/v1/*')) {
                return $envelope('Anda tidak memiliki hak akses untuk tindakan ini.', [], 403);
            }
        });

        $exceptions->render(function (ModelNotFoundException $e, Request $request) use ($envelope) {
            if ($request->is('api/v1/*')) {
                return $envelope('Data yang diminta tidak ditemukan.', [], 404);
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) use ($envelope) {
            if ($request->is('api/v1/*')) {
                return $envelope('Data yang diminta tidak ditemukan.', [], 404);
            }
        });

        $exceptions->render(function (HttpExceptionInterface $e, Request $request) use ($envelope) {
            if ($request->is('api/v1/*')) {
                // Pesan bawaan middleware `verified` masih berbahasa Inggris.
                $raw = $e->getMessage();
                $message = match (true) {
                    str_contains(strtolower($raw), 'not verified') => 'Email kampus Anda belum diverifikasi. Silakan verifikasi melalui halaman verifikasi OTP.',
                    $raw !== '' => $raw,
                    $e->getStatusCode() === 403 => 'Anda tidak memiliki hak akses untuk tindakan ini.',
                    default => 'Permintaan tidak dapat diproses.',
                };

                return $envelope($message, [], $e->getStatusCode());
            }
        });
    })->create();
