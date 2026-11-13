<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * saved_reports — pendukung halaman "Laporan Tersimpan" yang sudah ada di UI.
 *
 * Tidak ada di rancangan awal DATABASE_DESIGN.md, namun halaman bookmark sudah
 * menjadi bagian antarmuka sehingga datanya perlu disimpan di server, bukan
 * hanya di localStorage perangkat.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'report_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_reports');
    }
};
