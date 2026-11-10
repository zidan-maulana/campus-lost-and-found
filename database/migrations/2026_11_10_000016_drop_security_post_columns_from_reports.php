<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * DEC-028 — Konsep "pos satpam" dihapus dari skema.
 *
 * `storage_location` dan `security_post_id` hanya muncul di
 * docs/API_CONTRACT.md (asumsi tim front-end) dan tidak pernah ada di
 * PRD, SRS, DATABASE_DESIGN, maupun DECISIONS_LOG. Kedua kolom juga tidak
 * pernah terisi satu baris pun. Lokasi penitipan kini cukup dicatat pada
 * `reports.storage_note` sebagai teks bebas.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'storage_location')) {
                $table->dropColumn('storage_location');
            }

            if (Schema::hasColumn('reports', 'security_post_id')) {
                $table->dropColumn('security_post_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (! Schema::hasColumn('reports', 'storage_location')) {
                $table->string('storage_location', 30)->nullable();
            }

            if (! Schema::hasColumn('reports', 'security_post_id')) {
                $table->unsignedBigInteger('security_post_id')->nullable();
            }
        });
    }
};
