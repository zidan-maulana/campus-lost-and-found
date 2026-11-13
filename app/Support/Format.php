<?php

namespace App\Support;

use Carbon\CarbonInterface;

/**
 * Pemformatan tanggal/waktu bergaya Indonesia.
 *
 * Dipakai bersama oleh API dan view agar label tanggal yang tampil di UI
 * tetap sama persis seperti sebelumnya (mis. "20 Jun 2026").
 */
class Format
{
    private const MONTHS_SHORT = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
        7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
    ];

    private const MONTHS_LONG = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    /** "20 Jun 2026" */
    public static function dateShort(CarbonInterface|string|null $date): ?string
    {
        if (! $date) {
            return null;
        }

        $carbon = $date instanceof CarbonInterface ? $date : \Carbon\Carbon::parse($date);

        return $carbon->day.' '.self::MONTHS_SHORT[$carbon->month].' '.$carbon->year;
    }

    /** "20 Juni 2026" */
    public static function dateLong(CarbonInterface|string|null $date): ?string
    {
        if (! $date) {
            return null;
        }

        $carbon = $date instanceof CarbonInterface ? $date : \Carbon\Carbon::parse($date);

        return $carbon->day.' '.self::MONTHS_LONG[$carbon->month].' '.$carbon->year;
    }

    /** "10 mnt yang lalu", "3 jam yang lalu", "Kemarin, 16:45", "15 Jan 2026" */
    public static function relative(CarbonInterface|string|null $moment): string
    {
        if (! $moment) {
            return '';
        }

        $carbon = $moment instanceof CarbonInterface ? $moment : \Carbon\Carbon::parse($moment);
        $now = now();

        $diffMinutes = (int) $carbon->diffInMinutes($now, false);

        if ($diffMinutes < 1) {
            return 'Baru saja';
        }

        if ($diffMinutes < 60) {
            return $diffMinutes.' mnt yang lalu';
        }

        if ($diffMinutes < 24 * 60) {
            return (int) floor($diffMinutes / 60).' jam yang lalu';
        }

        if ($diffMinutes < 48 * 60) {
            return 'Kemarin, '.$carbon->format('H:i');
        }

        return self::dateShort($carbon);
    }

    /** Kategori waktu untuk pengelompokan daftar notifikasi: today | week | month */
    public static function timeCategory(CarbonInterface|string|null $moment): string
    {
        if (! $moment) {
            return 'month';
        }

        $carbon = $moment instanceof CarbonInterface ? $moment : \Carbon\Carbon::parse($moment);

        if ($carbon->isToday()) {
            return 'today';
        }

        if ($carbon->greaterThan(now()->subDays(7))) {
            return 'week';
        }

        return 'month';
    }

    /** "14.30 WIB (Siang)" dari jam kejadian. */
    public static function timeLabel(CarbonInterface|string|null $moment): ?string
    {
        if (! $moment) {
            return null;
        }

        $carbon = $moment instanceof CarbonInterface ? $moment : \Carbon\Carbon::parse($moment);
        $hour = (int) $carbon->format('H');

        $part = match (true) {
            $hour < 11 => 'Pagi',
            $hour < 15 => 'Siang',
            $hour < 18 => 'Sore',
            default => 'Malam',
        };

        return $carbon->format('H.i').' WIB ('.$part.')';
    }
}
