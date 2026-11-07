<?php

namespace App\Enums;

/**
 * Status laporan — SYSTEM_DESIGN.md 10 & DATABASE_DESIGN.md 4.3
 *
 * open -> in_handover -> resolved
 *   \-> cancelled
 *   \-> hidden (moderasi / penemu dinonaktifkan)
 */
enum ReportStatus: string
{
    case Open = 'open';
    case InHandover = 'in_handover';
    case Resolved = 'resolved';
    case Cancelled = 'cancelled';
    case Hidden = 'hidden';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Terbuka',
            self::InHandover => 'Proses Serah Terima',
            self::Resolved => 'Selesai',
            self::Cancelled => 'Dibatalkan',
            self::Hidden => 'Disembunyikan',
        };
    }

    /** Apakah laporan boleh muncul di katalog publik. */
    public function isPubliclyVisible(): bool
    {
        return in_array($this, [self::Open, self::InHandover, self::Resolved], true);
    }

    /** Apakah laporan masih bisa menerima klaim baru. */
    public function acceptsClaims(): bool
    {
        return $this === self::Open;
    }

    /** Status terminal bersifat permanen (BR-13). */
    public function isTerminal(): bool
    {
        return in_array($this, [self::Resolved, self::Cancelled, self::Hidden], true);
    }

    /** Laporan terkunci dari perubahan data. */
    public function isLocked(): bool
    {
        return in_array($this, [self::InHandover, self::Resolved, self::Cancelled, self::Hidden], true);
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Open => 'status-open',
            self::InHandover => 'status-handover',
            self::Resolved => 'status-resolved',
            self::Cancelled => 'status-cancelled',
            self::Hidden => 'status-hidden',
        };
    }
}
