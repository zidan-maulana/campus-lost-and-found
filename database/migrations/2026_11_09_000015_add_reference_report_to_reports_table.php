<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * DEC-027 — satu barang = satu laporan aktif.
 *
 * `reports.reference_report_id` menyimpan hubungan "laporan temuan ini dibuat
 * dari laporan hilang X". Sebelumnya kolom ini tidak ada, sehingga parameter
 * `reference_report_id` hanya dipakai untuk notifikasi lalu dibuang.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('reference_report_id')
                ->nullable()
                ->after('category_id')
                ->constrained('reports')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reference_report_id');
        });
    }
};
