<?php

namespace App\Models;

use App\Enums\ClaimStatus;
use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Support\Format;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'reference_report_id',
        'type',
        'title',
        'description',
        'proof_question',
        'campus_location',
        'location',
        'brand',
        'storage_note',
        'storage_location',
        'security_post_id',
        'incident_date',
        'incident_time',
        'image_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'type' => ReportType::class,
            'status' => ReportStatus::class,
            'incident_date' => 'date',
        ];
    }

    // ---------------------------------------------------------------- relasi

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    /** Pengguna yang menyimpan laporan ini (halaman "Laporan Tersimpan"). */
    public function savedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_reports')->withTimestamps();
    }

    public function handoverClaim(): ?Claim
    {
        return $this->claims()->where('status', ClaimStatus::InHandover->value)->first();
    }

    /** Laporan hilang yang menjadi acuan laporan temuan ini (DEC-027). */
    public function referenceReport(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'reference_report_id');
    }

    /** Laporan temuan yang dibuat dari laporan hilang ini. */
    public function linkedFoundReports(): HasMany
    {
        return $this->hasMany(Report::class, 'reference_report_id');
    }

    // ----------------------------------------------------------------- scope

    /**
     * Laporan yang masih berjalan dan boleh tampil di daftar/katalog publik.
     *
     * DEC-027: laporan yang sudah `resolved` (barang sudah kembali / sudah
     * ketemu) tidak lagi ditampilkan agar satu barang tidak muncul dua kali.
     */
    public function scopeListed(Builder $query): Builder
    {
        return $query->whereIn('status', [
            ReportStatus::Open->value,
            ReportStatus::InHandover->value,
        ]);
    }

    /** Laporan yang boleh dibuka lewat tautan langsung (detail), termasuk yang selesai. */
    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query->whereIn('status', [
            ReportStatus::Open->value,
            ReportStatus::InHandover->value,
            ReportStatus::Resolved->value,
        ]);
    }

    public function scopeOfType(Builder $query, ?ReportType $type): Builder
    {
        return $type ? $query->where('type', $type->value) : $query;
    }

    /** Pencarian kata kunci pada judul, deskripsi, merek, dan lokasi (FR-05). */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        $like = '%'.str_replace(['%', '_'], ['\%', '\_'], $term).'%';

        return $query->where(function (Builder $q) use ($like) {
            $q->where('title', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->orWhere('brand', 'like', $like)
                ->orWhere('location', 'like', $like)
                ->orWhere('campus_location', 'like', $like);
        });
    }

    // ---------------------------------------------------------------- bantuan

    public function isOwnedBy(?User $user): bool
    {
        return $user !== null && (int) $this->user_id === (int) $user->id;
    }

    public function isLost(): bool
    {
        return $this->type === ReportType::Lost;
    }

    public function isFound(): bool
    {
        return $this->type === ReportType::Found;
    }

    /** Boleh diubah/dibatalkan hanya oleh pemilik dan tidak terkunci (FR-04). */
    public function isEditableBy(?User $user): bool
    {
        return $this->isOwnedBy($user) && ! $this->status->isLocked();
    }

    public function canReceiveClaim(): bool
    {
        return $this->isFound() && $this->status->acceptsClaims();
    }

    /**
     * Label status untuk UI.
     *
     * Laporan hilang yang sudah `resolved` bukan berarti serah terima selesai,
     * melainkan barangnya sudah ketemu (DEC-027), jadi labelnya dibedakan.
     */
    public function statusLabel(): string
    {
        if ($this->isLost() && $this->status === ReportStatus::Resolved) {
            return 'Sudah Ketemu';
        }

        return $this->status->label();
    }

    /** Laporan hilang ini sudah ditutup karena barangnya ditemukan. */
    public function isClosedByMatch(): bool
    {
        return $this->isLost() && $this->status === ReportStatus::Resolved;
    }

    public function imageUrl(): string
    {
        return \App\Support\FindumbStorage::publicUrl($this->image_path)
            ?? asset('images/samples/bag.png');
    }

    /**
     * Bentuk data yang dipakai UI (kontrak lama localStorage) supaya tampilan
     * tidak perlu diubah sama sekali.
     *
     * @return array<string, mixed>
     */
    public function toUiArray(?User $viewer = null): array
    {
        $locationFull = trim(implode(' — ', array_filter([
            $this->campus_location,
            $this->location,
        ])));

        return [
            'id' => (string) $this->id,
            'type' => $this->type->value,
            'title' => $this->title,
            'brand' => $this->brand,
            'category' => $this->category?->name,
            'category_slug' => $this->category?->slug,
            'category_label' => $this->category?->name,
            'location_campus' => $this->campus_location,
            'location_specific' => $this->location,
            'location_full' => $locationFull,
            'date' => $this->incident_date?->format('Y-m-d'),
            'date_display' => Format::dateShort($this->incident_date),
            'time' => $this->incident_time,
            'storage' => $this->storage_note,
            'features' => $this->description,
            'image' => $this->imageUrl(),
            'status' => $this->status->value,
            'status_label' => $this->statusLabel(),
            'type_label' => $this->type->label(),
            // Pertanyaan verifikasi hanya untuk pengguna yang login (dipakai
            // saat mengajukan klaim), tidak untuk tamu.
            'proof_question' => $viewer !== null ? $this->proof_question : null,
            'reference_report_id' => $this->reference_report_id ? (string) $this->reference_report_id : null,
            'is_mine' => $this->isOwnedBy($viewer),
            'reporter_name' => $this->user?->name,
            'reporter_meta' => $this->user?->reporterMeta(),
            'reporter_initials' => $this->user?->initials(),
            'claims_count' => $this->claims_count ?? null,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
