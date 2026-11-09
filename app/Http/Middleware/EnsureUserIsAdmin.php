<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Otorisasi sisi server untuk area administrator.
 * Tautan admin di UI hanya kosmetik; pemeriksaan wajib di sini.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            abort($request->expectsJson() ? 403 : 404, $request->expectsJson()
                ? 'Anda tidak memiliki hak akses untuk tindakan ini.'
                : '');
        }

        return $next($request);
    }
}
