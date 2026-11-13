<?php

namespace App\Enums;

enum AbuseReportStatus: string
{
    case Pending = 'pending';
    case Reviewing = 'reviewing';
    case Resolved = 'resolved';
    case Dismissed = 'dismissed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Tinjauan',
            self::Reviewing => 'Sedang Ditinjau',
            self::Resolved => 'Selesai Ditindak',
            self::Dismissed => 'Diabaikan',
        };
    }
}
