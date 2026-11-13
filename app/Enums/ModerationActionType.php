<?php

namespace App\Enums;

enum ModerationActionType: string
{
    case Warning = 'warning';
    case HideReport = 'hide_report';
    case UnhideReport = 'unhide_report';
    case SuspendUser = 'suspend_user';
    case ReactivateUser = 'reactivate_user';

    public function label(): string
    {
        return match ($this) {
            self::Warning => 'Peringatan',
            self::HideReport => 'Sembunyikan Laporan',
            self::UnhideReport => 'Tampilkan Laporan',
            self::SuspendUser => 'Nonaktifkan Akun',
            self::ReactivateUser => 'Aktifkan Akun',
        };
    }
}
