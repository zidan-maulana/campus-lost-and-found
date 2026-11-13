<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'claim_id',
        'sender_id',
        'message',
        'read_at',
        'is_retained',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'is_retained' => 'boolean',
        ];
    }

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /** @return array<string, mixed> */
    public function toUiArray(?User $viewer = null): array
    {
        return [
            'id' => (string) $this->id,
            'claim_id' => (string) $this->claim_id,
            'sender_id' => (string) $this->sender_id,
            'sender_name' => $this->sender?->name,
            'message' => $this->message,
            'is_mine' => $viewer !== null && (int) $this->sender_id === (int) $viewer->id,
            'time_display' => $this->created_at?->format('H:i'),
            'created_at' => $this->created_at?->toIso8601String(),
            'read_at' => $this->read_at?->toIso8601String(),
        ];
    }
}
