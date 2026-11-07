<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan peran (role) dan status akun pada tabel users sesuai
 * DATABASE_DESIGN.md 4.1 dan DEC-026.
 *
 * Nilai:
 * - role   : user | admin
 * - status : active | suspended
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'admin'])->default('user')->after('password');
            $table->enum('status', ['active', 'suspended'])->default('active')->after('role');
            $table->string('nim', 20)->nullable()->after('name');
            $table->string('phone', 25)->nullable()->after('nim');
            $table->string('avatar_path')->nullable()->after('phone');
            $table->timestamp('last_seen_at')->nullable()->after('email_verified_at');

            $table->index(['role', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role', 'status']);
            $table->dropIndex(['status']);
            $table->dropColumn(['role', 'status', 'nim', 'phone', 'avatar_path', 'last_seen_at']);
        });
    }
};
