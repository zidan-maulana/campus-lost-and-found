<?php

namespace App\Models;

use App\Enums\ClaimStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'claimant_id',
        'status',
        'cancellation_reason',
        'cancelled_by',
        'submitted_at',
        'response_notified_at',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ClaimStatus::class,
            'submitted_at' => 'datetime',
            'response_notified_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    // ---------------------------------------------------------------- relasi

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function claimant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'claimant_id');
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ClaimStatusHistory::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function confirmations(): HasMany
    {
        return $this->hasMany(HandoverConfirmation::class);
    }

    // ----------------------------------------------------------------- scope

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ClaimStatus::activeValues());
    }

    public function scopeSubmitted(Builder $query): Builder
    {
        return $query->where('status', ClaimStatus::Submitted->value);
    }

    // ---------------------------------------------------------------- bantuan

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function isTerminal(): bool
    {
        return $this->status->isTerminal();
    }

    public function finder(): ?User
    {
        return $this->report?->user;
    }

    public function isParticipant(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        return (int) $this->claimant_id === (int) $user->id
            || (int) ($this->report?->user_id) === (int) $user->id;
    }

    public function isClaimant(?User $user): bool
    {
        return $user !== null && (int) $this->claimant_id === (int) $user->id;
    }

    public function isFinder(?User $user): bool
    {
        return $user !== null && (int) ($this->report?->user_id) === (int) $user->id;
    }

    /** Batas waktu kedaluwarsa: 120 jam sejak klaim dibuat (DEC-024). */
    public function expiresAt(): ?\Illuminate\Support\Carbon
    {
        $base = $this->submitted_at ?? $this->created_at;

        return $base?->copy()->addHours((int) config('findumb.claim.expiry_hours', 120));
    }

    public function expiresInHours(): ?float
    {
        $expiresAt = $this->expiresAt();

        if (! $expiresAt) {
            return null;
        }

        return round(max(0, now()->diffInHours($expiresAt, false)), 1);
    }

    /** @return array<string, mixed> */
    public function toUiArray(?User $viewer = null): array
    {
        return [
            'id' => (string) $this->id,
            'report_id' => (string) $this->report_id,
            'claimant_id' => (string) $this->claimant_id,
            'claimant_name' => $this->claimant?->name,
            'claimant_meta' => $this->claimant?->reporterMeta(),
            'claimant_initials' => $this->claimant?->initials(),
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'is_active' => $this->isActive(),
            'can_send_message' => $this->status->allowsChat(),
            'i_am_claimant' => $this->isClaimant($viewer),
            'i_am_finder' => $this->isFinder($viewer),
            'confirmations_count' => $this->confirmations_count ?? $this->confirmations()->count(),
            'i_confirmed' => $viewer ? $this->confirmations()->where('user_id', $viewer->id)->exists() : false,
            'submitted_at' => $this->submitted_at?->toIso8601String(),
            'expires_at' => $this->expiresAt()?->toIso8601String(),
            'expires_in_hours' => $this->expiresInHours(),
            'resolved_at' => $this->resolved_at?->toIso8601String(),
        ];
    }
}
