<?php

namespace App\Enums;

enum ReportType: string
{
    case Lost = 'lost';
    case Found = 'found';

    public function label(): string
    {
        return match ($this) {
            self::Lost => 'Hilang',
            self::Found => 'Ditemukan',
        };
    }

    /** Label panjang untuk badge di UI. */
    public function badgeLabel(): string
    {
        return match ($this) {
            self::Lost => 'Barang Hilang',
            self::Found => 'Barang Temuan',
        };
    }
}
