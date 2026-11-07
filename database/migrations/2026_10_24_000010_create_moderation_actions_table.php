<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * moderation_actions — DATABASE_DESIGN.md 4.10
 * Audit trail tindakan administrator.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moderation_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('target_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('abuse_report_id')->nullable()->constrained('abuse_reports')->nullOnDelete();
            $table->enum('action_type', ['warning', 'hide_report', 'unhide_report', 'suspend_user', 'reactivate_user']);
            $table->text('reason');
            $table->string('target_type', 30)->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['target_user_id', 'created_at']);
            $table->index(['action_type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moderation_actions');
    }
};
