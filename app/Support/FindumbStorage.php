<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Pemilih disk penyimpanan yang dipakai untuk unggahan publik.
 *
 * Prioritas: Supabase Storage bila kredensialnya tersedia (di lingkungan
 * produksi/serverless), selain itu kembali ke disk lokal public (development).
 */
class FindumbStorage
{
    public static function disk(): string
    {
        $url = (string) env('SUPABASE_URL');
        $key = (string) env('SUPABASE_S3_ACCESS_KEY');
        $secret = (string) env('SUPABASE_S3_SECRET_KEY');

        if ($url !== '' && ! str_contains($url, 'placeholder') && $key !== '' && $secret !== '') {
            return 'supabase';
        }

        return 'public';
    }

    public static function isSupabase(): bool
    {
        return self::disk() === 'supabase';
    }

    /** URL publik untuk path tersimpan (local maupun Supabase). */
    public static function publicUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, 'images/')) {
            return asset($path);
        }

        return Storage::disk(self::disk())->url($path);
    }

    public static function delete(?string $path): void
    {
        if ($path && ! Str::startsWith($path, ['http://', 'https://', 'images/'])) {
            Storage::disk(self::disk())->delete($path);
        }
    }
}