<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menyelaraskan skema database dengan docs/API_CONTRACT.md (kontrak front-end Zidan).
 *
 * Yang ditambahkan:
 *  - reports.proof_question    : pertanyaan rahasia verifikasi pemilik (wajib untuk type=found).
 *  - reports.storage_location  : 'self' | 'security_post' (titipan satpam).
 *  - reports.security_post_id  : pos satpam tempat barang dititipkan.
 *  - claims.proof_answer       : jawaban pengklaim atas proof_question.
 *  - claims.claim_reason       : alasan/cerita pengklaim.
 *  - claims.proof_attachment   : path foto bukti tambahan (opsional).
 *  - claims.rejection_reason   : alasan penemu menolak klaim.
 *
 * Catatan: kontrak memakai nama `campus` (enum), sedangkan kolom lama bernama
 * `campus_location`. Tidak dibuat kolom baru agar tidak ada dua sumber kebenaran —
 * parameter `campus` pada API v1 dipetakan ke kolom `campus_location`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('proof_question', 255)->nullable()->after('description');
            $table->string('storage_location', 20)->nullable()->after('storage_note');
            $table->unsignedBigInteger('security_post_id')->nullable()->after('storage_location');
        });

        Schema::table('claims', function (Blueprint $table) {
            $table->text('proof_answer')->nullable()->after('status');
            $table->text('claim_reason')->nullable()->after('proof_answer');
            $table->string('proof_attachment', 255)->nullable()->after('claim_reason');
            $table->text('rejection_reason')->nullable()->after('cancellation_reason');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['proof_question', 'storage_location', 'security_post_id']);
        });

        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn(['proof_answer', 'claim_reason', 'proof_attachment', 'rejection_reason']);
        });
    }
};
