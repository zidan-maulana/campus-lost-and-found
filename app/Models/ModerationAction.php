<?php

namespace App\Models;

use App\Enums\ModerationActionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModerationAction extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'admin_id',
        'target_user_id',
        'abuse_report_id',
        'action_type',
        'reason',
        'target_type',
        'target_id',
    ];

    protected function casts(): array
    {
        return [
            'action_type' => ModerationActionType::class,
            'created_at' => 'datetime',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function abuseReport(): BelongsTo
    {
        return $this->belongsTo(AbuseReport::class);
    }
}
