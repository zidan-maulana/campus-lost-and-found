<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpCodeMail;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * Verifikasi email via kode OTP 6 digit — melayani layar
 * `resources/views/auth/verify-otp.blade.php` milik front-end.
 *
 * Jalur tautan bertanda tangan (`verification.verify`) tetap tersedia sebagai
 * alternatif; keduanya menandai `email_verified_at` yang sama.
 */
class OtpController extends Controller
{
    public function notice(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        // Kirim kode pertama bila belum ada / sudah kedaluwarsa.
        if (! $user->email_otp_hash || $user->email_otp_expires_at?->isPast()) {
            $this->sendCode($user);
        }

        return view(view()->exists('auth.verify-otp') ? 'auth.verify-otp' : 'auth.verify-email', [
            // Layar OTP front-end menampilkan alamat tujuan kode.
            'pending_email' => $user->email,
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size' => 'Kode OTP harus terdiri dari 6 digit angka.',
        ]);

        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        if (! $user->verifyEmailOtp($validated['otp'])) {
            return back()->withErrors([
                'otp' => 'Kode OTP salah atau sudah kedaluwarsa. Minta kode baru bila perlu.',
            ]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('home')
            ->with('status', 'Email kampus berhasil diverifikasi. Selamat datang di UMBFind!');
    }

    public function resend(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $this->sendCode($user);

        return back()->with('status', 'Kode OTP baru telah dikirim ke email kampus Anda.');
    }

    private function sendCode(User $user): void
    {
        $code = $user->issueEmailOtp();

        Mail::to($user->email)->send(new OtpCodeMail($user, $code));
    }
}
