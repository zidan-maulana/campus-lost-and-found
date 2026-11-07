<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'category_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Kategori aktif untuk dropdown filter.
     *
     * Di-cache karena isinya hampir tidak pernah berubah, sementara query ini
     * dipanggil di setiap halaman. Cache dibersihkan otomatis saat kategori
     * disimpan atau dihapus (lihat booted()).
     *
     * @return \Illuminate\Support\Collection<int, self>
     */
    public static function cachedActive()
    {
        return \Illuminate\Support\Facades\Cache::rememberForever(
            self::CACHE_ACTIVE,
            fn () => self::query()->active()->ordered()->get()
        );
    }

    /**
     * Peta slug => id yang dipakai jembatan data di layout.
     *
     * @return \Illuminate\Support\Collection<string, int>
     */
    public static function cachedSlugMap()
    {
        return \Illuminate\Support\Facades\Cache::rememberForever(
            self::CACHE_SLUG_MAP,
            fn () => self::query()->pluck('id', 'slug')
        );
    }

    public const CACHE_ACTIVE = 'findumb.categories.active';

    public const CACHE_SLUG_MAP = 'findumb.categories.slug-map';

    protected static function booted(): void
    {
        $flush = function (): void {
            \Illuminate\Support\Facades\Cache::forget(self::CACHE_ACTIVE);
            \Illuminate\Support\Facades\Cache::forget(self::CACHE_SLUG_MAP);
        };

        static::saved($flush);
        static::deleted($flush);
    }
}
