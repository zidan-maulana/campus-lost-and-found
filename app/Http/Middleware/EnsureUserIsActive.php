<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Memblokir akun yang ditangguhkan (users.status = suspended).
 * Status akun diperiksa setiap request, bukan hanya saat login.
 */
class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isSuspended()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = 'Akun Anda sedang ditangguhkan oleh administrator. Hubungi pengelola platform untuk informasi lebih lanjut.';

            if ($request->is('api/v1/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => (object) [],
                    'code' => 403,
                ], 403);
            }

            return $request->expectsJson()
                ? response()->json(['message' => $message], 403)
                : redirect()->route('login')->withErrors(['email' => $message]);
        }

        return $next($request);
    }
}
