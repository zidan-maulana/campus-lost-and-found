<?php

namespace App\Models;

use App\Support\Format;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Notifikasi dalam aplikasi (DATABASE_DESIGN.md 4.8).
 *
 * Dinamai AppNotification agar tidak bentrok dengan
 * Illuminate\Support\Facades\Notification, tabel tetap `notifications`.
 */
class AppNotification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'data',
        'target_url',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /** Ikon Lucide per jenis notifikasi. */
    public function icon(): string
    {
        return match ($this->type) {
            'claim' => 'shield-alert',
            'claim_reminder' => 'clock-3',
            'claim_expired' => 'timer-off',
            'handover' => 'key-round',
            'message' => 'message-square',
            'system', 'report' => 'check-circle-2',
            'warning' => 'alert-triangle',
            default => 'bell',
        };
    }

    public function tag(): string
    {
        return match ($this->type) {
            'claim' => 'Klaim Masuk',
            'claim_reminder' => 'Pengingat 72 Jam',
            'claim_expired' => 'Klaim Kedaluwarsa',
            'handover' => 'Serah Terima',
            'message' => 'Pesan Baru',
            'report' => 'Laporan Terbit',
            'warning' => 'Peringatan',
            default => 'Sistem',
        };
    }

    /** @return array<string, mixed> */
    public function toUiArray(): array
    {
        return [
            'id' => 'notif_'.$this->id,
            'type' => $this->type,
            'time_category' => Format::timeCategory($this->created_at),
            'icon' => $this->icon(),
            'tag' => $this->tag(),
            'title' => $this->title,
            'body' => $this->body,
            'time_display' => Format::relative($this->created_at),
            'target_url' => $this->target_url,
            'read' => $this->isRead(),
            'timestamp' => $this->created_at?->toIso8601String(),
        ];
    }
}
