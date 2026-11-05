<?php

namespace App\Models\Layanan;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Fasilitas extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    protected $fillable = [
        'jeneng',
        'tandha',
        'katrangan',
        'path_gambar',
        'path_thumb',
        'urutan',
        'urip',
    ];

    protected function casts(): array
    {
        return [
            'urip'   => 'boolean',
            'urutan' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            if (blank($model->tandha)) {
                $model->tandha = self::uniqueSlug($model->jeneng, $model->id);
            }
        });
    }

    public static function uniqueSlug(?string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name ?: 'fasilitas');
        $slug = $base;
        $i    = 1;

        while (self::withTrashed()
            ->where('tandha', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    /* ---------- Scopes ---------- */

    public function scopeFiltered(Builder $q, array $f): Builder
    {
        return $q
            ->when(($f['status'] ?? null) === 'active',   fn($b) => $b->active())
            ->when(($f['status'] ?? null) === 'inactive', fn($b) => $b->inactive())
            ->when(! empty($f['q']), function ($b) use ($f) {
                $t = '%' . str_replace(['%', '_'], ['\%', '\_'], trim($f['q'])) . '%';
                $b->where('jeneng', 'like', $t);
            });
    }

    /* ---------- URL helpers ---------- */

    public function getGambarUrlAttribute(): ?string
    {
        return $this->path_gambar ? asset('storage/' . $this->path_gambar) : null;
    }

    public function getThumbUrlAttribute(): ?string
    {
        return $this->path_thumb
            ? asset('storage/' . $this->path_thumb)
            : $this->gambar_url;
    }
}
