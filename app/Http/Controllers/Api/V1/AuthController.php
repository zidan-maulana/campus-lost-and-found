<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ClaimStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Api\ApiPresenter;
use App\Support\Api\V1Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Modul autentikasi API v1 — docs/API_CONTRACT.md §4.
 */
class AuthController extends Controller
{
    use V1Response;

    /**
     * POST /api/v1/auth/login (§4.1)
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $email = strtolower(trim($validated['email']));

        if (! Auth::attempt(['email' => $email, 'password' => $validated['password']], (bool) ($validated['remember'] ?? false))) {
            return $this->fail('Email atau kata sandi tidak sesuai.', [
                'email' => ['Email atau kata sandi tidak sesuai.'],
            ], 422);
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = $request->user();

        // DEC-026 — akun ditangguhkan tidak boleh masuk.
        if ($user->isSuspended()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $this->fail('Akun Anda sedang ditangguhkan oleh administrator.', [
                'email' => ['Akun Anda sedang ditangguhkan oleh administrator.'],
            ], 403);
        }

        $user->forceFill(['last_seen_at' => now()])->save();

        return $this->ok([
            'user' => ApiPresenter::user($user),
            // Aplikasi web memakai session cookie Laravel (Sanctum belum dipasang).
            'token' => null,
            'auth_mode' => 'session',
        ], 'Login berhasil.');
    }

    /**
     * POST /api/v1/auth/register (§4.2)
     */
    public function register(Request $request): JsonResponse
    {
        $domains = array_map('strtolower', config('findumb.campus_email_domains', []));

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nim' => ['sometimes', 'nullable', 'string', 'max:20'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:25'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $email = strtolower(trim($validated['email']));
        $domain = strtolower((string) substr((string) strrchr($email, '@'), 1));

        if (! empty($domains) && ! in_array($domain, $domains, true)) {
            return $this->fail('Validasi formulir gagal. Silakan periksa kembali data Anda.', [
                'email' => ['Email harus menggunakan domain resmi kampus ('.implode(', ', $domains).').'],
            ], 422);
        }

        $user = DB::transaction(fn () => User::create([
            'name' => trim($validated['name']),
            'nim' => isset($validated['nim']) ? trim((string) $validated['nim']) : null,
            'phone' => isset($validated['phone']) ? trim((string) $validated['phone']) : null,
            'email' => $email,
            'password' => Hash::make($validated['password']),
            'role' => UserRole::User,
            'status' => UserStatus::Active,
        ]));

        Auth::login($user);
        $request->session()->regenerate();

        $user->sendEmailVerificationNotification();

        return $this->created([
            'user' => ApiPresenter::user($user),
            'token' => null,
            'auth_mode' => 'session',
        ], 'Registrasi berhasil. Silakan verifikasi email kampus Anda.');
    }

    /**
     * GET /api/v1/auth/me (§4.3)
     */
    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        return $this->ok([
            'user' => ApiPresenter::user($user),
            'stats' => [
                'reports_total' => $user->reports()->count(),
                'reports_active' => $user->reports()->whereIn('status', ['open', 'in_handover'])->count(),
                'claims_total' => $user->claims()->count(),
                'claims_active' => $user->claims()->whereIn('status', ClaimStatus::activeValues())->count(),
                'unread_notifications' => $user->notifications()->whereNull('read_at')->count(),
            ],
        ], 'Profil pengguna aktif.');
    }

    /**
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->ok(null, 'Logout berhasil.');
    }
}
