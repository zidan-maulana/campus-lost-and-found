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
        'type',
        'title',
        'description',
        'campus_location',
        'location',
        'brand',
        'storage_note',
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

    // ----------------------------------------------------------------- scope

    /** Laporan yang boleh tampil di katalog publik (bukan hidden/cancelled). */
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
            'status_label' => $this->status->label(),
            'type_label' => $this->type->label(),
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
