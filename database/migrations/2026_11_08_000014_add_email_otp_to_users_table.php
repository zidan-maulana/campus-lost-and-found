<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Kode OTP 6 digit untuk layar `auth/verify-otp.blade.php` milik front-end.
 *
 * Backend tetap menyimpan tautan verifikasi bertanda tangan (route
 * `verification.verify`) sebagai jalur utama; OTP menjadi jalur kedua yang
 * dipakai layar verifikasi front-end.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email_otp_hash')->nullable()->after('email_verified_at');
            $table->timestamp('email_otp_expires_at')->nullable()->after('email_otp_hash');
            $table->unsignedTinyInteger('email_otp_attempts')->default(0)->after('email_otp_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_otp_hash', 'email_otp_expires_at', 'email_otp_attempts']);
        });
    }
};
