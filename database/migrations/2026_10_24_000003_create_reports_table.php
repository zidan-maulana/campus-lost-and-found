<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * reports — DATABASE_DESIGN.md 4.3
 *
 * Kolom tambahan di luar rancangan awal (dibutuhkan agar kontrak data UI
 * terpenuhi tanpa mengubah tampilan): brand, campus_location, storage_note,
 * incident_time. Semua nullable sehingga tidak memaksa UI berubah.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('item_categories')->nullOnDelete();

            $table->enum('type', ['lost', 'found']);
            $table->string('title', 150);
            $table->text('description')->nullable();

            // Lokasi
            $table->string('campus_location', 120)->nullable();
            $table->string('location', 180)->nullable();

            // Detail barang
            $table->string('brand', 120)->nullable();
            $table->string('storage_note', 150)->nullable();

            // Waktu kejadian
            $table->date('incident_date')->nullable();
            $table->string('incident_time', 60)->nullable();

            $table->string('image_path')->nullable();

            $table->enum('status', ['open', 'in_handover', 'resolved', 'cancelled', 'hidden'])
                ->default('open');

            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index(['status', 'created_at']);
            $table->index('incident_date');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
