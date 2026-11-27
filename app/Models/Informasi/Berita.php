<?php

namespace App\Models\Informasi;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use App\Models\User;
use App\Services\Landing\Informasi\BeritaService;
use App\Services\Landing\LandingHomeService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Berita extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    public const GOLONGAN = [
        'pengumuman' => 'Pengumuman',
        'akademik' => 'Akademik',
        'kemahasiswaan' => 'Kemahasiswaan',
        'prestasi' => 'Prestasi',
        'umum' => 'Umum',
    ];

    protected $fillable = [
        'digawe_dening',
        'judhul',
        'tandha',
        'golongan',
        'ringkesan',
        'isi',
        'path_sampul',
        'path_thumb',
        'wektu_terbit',
        'urutan',
        'urip',
    ];

    protected function casts(): array
    {
        return [
            'urip' => 'boolean',
            'urutan' => 'integer',
            'wektu_terbit' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        $flush = function () {
            app(LandingHomeService::class)->forgetBerita();
            app(BeritaService::class)->clearCache();
        };

        static::saved($flush);
        static::deleted($flush);
        static::restored($flush);
    }

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'digawe_dening');
    }

    public function scopeTerbit(Builder $query): Builder
    {
        return $query
            ->active()
            ->whereNotNull('wektu_terbit')
            ->where('wektu_terbit', '<=', now());
    }

    public function getSampulUrlAttribute(): ?string
    {
        return $this->path_sampul
            ? asset('storage/' . $this->path_sampul)
            : null;
    }

    public function getThumbUrlAttribute(): ?string
    {
        if ($this->path_thumb) {
            return asset('storage/' . $this->path_thumb);
        }

        return $this->sampul_url;
    }
}
