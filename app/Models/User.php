<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nim',
        'phone',
        'email',
        'password',
        'role',
        'status',
        'avatar_path',
        'preferences',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_otp_hash',
    ];

    /**
     * Nilai bawaan di level model, agar instance yang baru dibuat langsung
     * memiliki status/role yang benar tanpa perlu refresh dari database.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'role' => 'user',
        'status' => 'active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_otp_expires_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'password' => 'hashed',
            'preferences' => 'array',
            'role' => UserRole::class,
            'status' => UserStatus::class,
        ];
    }

    // ------------------------------------------------------ verifikasi OTP

    /**
     * Terbitkan kode OTP 6 digit baru (berlaku 15 menit, maksimal 5 percobaan).
     *
     * @return string kode mentah yang dikirim ke email pengguna
     */
    public function issueEmailOtp(int $ttlMinutes = 15): string
    {
        $code = (string) random_int(100000, 999999);

        $this->forceFill([
            'email_otp_hash' => \Illuminate\Support\Facades\Hash::make($code),
            'email_otp_expires_at' => now()->addMinutes($ttlMinutes),
            'email_otp_attempts' => 0,
        ])->save();

        return $code;
    }

    /**
     * Validasi kode OTP. Mengembalikan true dan menghapus kode bila cocok.
     */
    public function verifyEmailOtp(string $code, int $maxAttempts = 5): bool
    {
        if (! $this->email_otp_hash || $this->email_otp_expires_at === null) {
            return false;
        }

        if ($this->email_otp_expires_at->isPast()) {
            return false;
        }

        if ((int) $this->email_otp_attempts >= $maxAttempts) {
            return false;
        }

        if (! \Illuminate\Support\Facades\Hash::check($code, (string) $this->email_otp_hash)) {
            $this->forceFill(['email_otp_attempts' => (int) $this->email_otp_attempts + 1])->save();

            return false;
        }

        $this->forceFill([
            'email_otp_hash' => null,
            'email_otp_expires_at' => null,
            'email_otp_attempts' => 0,
        ])->save();

        return true;
    }

    // ---------------------------------------------------------------- relasi

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class, 'claimant_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(AppNotification::class);
    }

    /** Laporan yang disimpan (bookmark) oleh pengguna ini. */
    public function savedReports(): BelongsToMany
    {
        return $this->belongsToMany(Report::class, 'saved_reports')->withTimestamps();
    }

    public function abuseReports(): HasMany
    {
        return $this->hasMany(AbuseReport::class, 'reporter_id');
    }

    // --------------------------------------------------------------- bantuan

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::Active;
    }

    public function isSuspended(): bool
    {
        return $this->status === UserStatus::Suspended;
    }

    /**
     * Klaim aktif pengguna (maksimal satu, dijaga unique index di database).
     */
    public function activeClaim(): ?Claim
    {
        return $this->claims()
            ->whereIn('status', \App\Enums\ClaimStatus::activeValues())
            ->first();
    }

    public function hasActiveClaim(): bool
    {
        return $this->activeClaim() !== null;
    }

    /** Inisial nama untuk avatar teks. */
    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim((string) $this->name)) ?: [];
        $initials = '';

        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        }

        return $initials !== '' ? $initials : 'U';
    }

    /** "Civitas Terverifikasi • NIM 4152***" — meta ringkas seperti di UI. */
    public function reporterMeta(): string
    {
        if ($this->nim) {
            $masked = mb_substr($this->nim, 0, 4).'***';

            return 'Civitas Terverifikasi • NIM '.$masked;
        }

        return $this->isAdmin() ? 'Administrator Platform' : 'Civitas Terverifikasi';
    }
}
