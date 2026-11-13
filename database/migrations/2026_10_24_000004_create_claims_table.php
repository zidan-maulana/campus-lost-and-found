<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * claims — DATABASE_DESIGN.md 4.4
 *
 * Integritas tingkat database:
 * - Partial unique index: satu pengklaim hanya boleh punya SATU klaim aktif
 *   (submitted / in_handover) di seluruh sistem (BR-05).
 * - Partial unique index: satu laporan hanya boleh punya SATU klaim
 *   in_handover pada satu waktu (BR-04).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->foreignId('claimant_id')->constrained('users')->cascadeOnDelete();

            $table->enum('status', ['submitted', 'in_handover', 'completed', 'rejected', 'cancelled', 'expired'])
                ->default('submitted');

            $table->text('cancellation_reason')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('response_notified_at')->nullable();
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            $table->index(['report_id', 'status']);
            $table->index(['claimant_id', 'status']);
            $table->index(['status', 'submitted_at']);
        });

        // Satu klaim aktif per pengguna (BR-05) — dijaga database, bukan hanya aplikasi.
        DB::statement(
            "CREATE UNIQUE INDEX claims_one_active_per_claimant
             ON claims (claimant_id)
             WHERE status IN ('submitted', 'in_handover')"
        );

        // Satu klaim in_handover per laporan (BR-04).
        DB::statement(
            "CREATE UNIQUE INDEX claims_one_handover_per_report
             ON claims (report_id)
             WHERE status = 'in_handover'"
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
