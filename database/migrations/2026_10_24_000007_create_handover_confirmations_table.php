<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * handover_confirmations — DATABASE_DESIGN.md 4.7
 * Unik (claim_id, user_id) untuk mencegah konfirmasi ganda oleh pihak yang sama.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('handover_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained('claims')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('confirmed_at');
            $table->timestamps();

            $table->unique(['claim_id', 'user_id'], 'unique_claim_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('handover_confirmations');
    }
};
