<?php

namespace App\Models\Landing;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Popup extends Model
{
    use SoftDeletes;

    public const CACHE_KEY = 'landing:popup_slides:v1';

    protected $table = 'popups';

    protected $fillable = [
        'kategori',
        'badge',
        'judul',
        'deskripsi',
        'gambar',
        'teks_tombol',
        'url_tombol',
        'buka_tab_baru',
        'teks_sekunder',
        'url_sekunder',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'buka_tab_baru' => 'boolean',
        'is_active'     => 'boolean',
        'urutan'        => 'integer',
    ];

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('id');
    }

    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    protected static function booted(): void
    {
        static::saved(fn() => self::flushCache());
        static::deleted(fn() => self::flushCache());
        static::restored(fn() => self::flushCache());
        static::forceDeleted(fn() => self::flushCache());
    }
}
