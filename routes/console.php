<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Penjadwalan tugas otomatis (SYSTEM_DESIGN 12.4, DEC-012, DEC-024, BR-09)
|--------------------------------------------------------------------------
| Dijalankan oleh cron: * * * * * php artisan schedule:run
*/

// Pengingat 72 jam untuk klaim yang belum direspons penemu.
Schedule::command('findumb:claim-reminders')
    ->hourly()
    ->withoutOverlapping()
    ->onOneServer();

// Klaim kedaluwarsa otomatis setelah 120 jam.
Schedule::command('findumb:expire-claims')
    ->hourly()
    ->withoutOverlapping()
    ->onOneServer();

// Hapus pesan chat 30 hari setelah serah terima selesai.
Schedule::command('findumb:purge-chat')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->onOneServer();
