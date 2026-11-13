<?php

namespace App\Enums;

/**
 * Status klaim — SYSTEM_DESIGN.md 10 & DATABASE_DESIGN.md 4.4
 *
 * Aktif    : submitted, in_handover
 * Terminal : completed, rejected, cancelled, expired  (permanen, BR-13)
 */
enum ClaimStatus: string
{
    case Submitted = 'submitted';
    case InHandover = 'in_handover';
    case Completed = 'completed';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Submitted => 'Menunggu Respons',
            self::InHandover => 'Proses Serah Terima',
            self::Completed => 'Selesai',
            self::Rejected => 'Ditolak',
            self::Cancelled => 'Dibatalkan',
            self::Expired => 'Kedaluwarsa',
        };
    }

    public function isActive(): bool
    {
        return in_array($this, [self::Submitted, self::InHandover], true);
    }

    public function isTerminal(): bool
    {
        return ! $this->isActive();
    }

    /** Chat hanya bisa dikirim selama klaim aktif (DEC-009/DEC-010). */
    public function allowsChat(): bool
    {
        return $this->isActive();
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Submitted => 'status-open',
            self::InHandover => 'status-handover',
            self::Completed => 'status-resolved',
            self::Rejected => 'status-cancelled',
            self::Cancelled => 'status-cancelled',
            self::Expired => 'status-hidden',
        };
    }

    /** @return array<int, string> */
    public static function activeValues(): array
    {
        return [self::Submitted->value, self::InHandover->value];
    }
}
