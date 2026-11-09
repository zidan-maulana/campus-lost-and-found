<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\OtpCodeMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * Autentikasi (PR-01, FR-01, FR-02).
 *
 * Fitur inti hanya tersedia setelah email kampus diverifikasi (BR-02).
 */
class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login', [
            // `mode` dipakai view front-end (satu file untuk login & daftar).
            'mode' => 'login',
        ]);
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau kata sandi tidak sesuai.']);
        }

        $request->session()->regenerate();

        $user = $request->user();

        // DEC-026 — akun ditangguhkan tidak boleh masuk.
        if ($user->isSuspended()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun Anda sedang ditangguhkan oleh administrator.']);
        }

        $user->forceFill(['last_seen_at' => now()])->save();

        return redirect()->intended($this->homeFor($user));
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register', [
            'domains' => config('findumb.campus_email_domains', []),
            'mode' => 'register',
        ]);
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = DB::transaction(fn () => User::create([
            'name' => $request->string('name')->trim()->value(),
            'nim' => $request->filled('nim') ? $request->string('nim')->trim()->value() : null,
            'phone' => $request->filled('phone') ? $request->string('phone')->trim()->value() : null,
            'email' => strtolower($request->string('email')->trim()->value()),
            'password' => Hash::make($request->string('password')->value()),
            'role' => UserRole::User,
            'status' => UserStatus::Active,
        ]));

        Auth::login($user);
        $request->session()->regenerate();

        // Kirim kode OTP 6 digit ke email kampus (BR-02) — dipakai layar verifikasi front-end.
        Mail::to($user->email)->send(new OtpCodeMail($user, $user->issueEmailOtp()));

        return redirect()->route('verification.notice')
            ->with('status', 'Akun berhasil dibuat. Masukkan kode OTP yang dikirim ke email kampus Anda.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function homeFor(User $user): string
    {
        return $user->isAdmin() ? route('admin.dashboard') : route('home');
    }
}
