<?php

namespace App\Models\Lembaga;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Lppm extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    public const CACHE_KEY = 'landing.lembaga.lppm.forPage.v1';

    protected $table = 'lppms';

    public const KATEGORI = [
        'profil'     => 'Profil & Layanan LPPM',
        'penelitian' => 'Penelitian & Hibah',
        'pengabdian' => 'Pengabdian kepada Masyarakat',
        'publikasi'  => 'Publikasi & Jurnal Ilmiah',
        'hki'        => 'Sentra HKI & Inovasi',
    ];

    protected $fillable = [
        'judhul',
        'tandha',
        'kategori',
        'isi',
        'data_tambahan',
        'urutan',
        'urip',
    ];

    protected function casts(): array
    {
        return [
            'urip'          => 'boolean',
            'urutan'        => 'integer',
            'data_tambahan' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Lppm $model) {
            if (blank($model->tandha) && filled($model->judhul)) {
                $model->tandha = Str::slug($model->judhul);
            }
        });

        $flush = fn() => Cache::forget(self::CACHE_KEY);

        static::saved($flush);
        static::deleted($flush);
        static::restored($flush);
    }

    public function getKategoriLabelAttribute(): string
    {
        return self::KATEGORI[$this->kategori] ?? ucfirst((string) $this->kategori);
    }

    public function meta(string $key, mixed $default = null): mixed
    {
        return data_get($this->data_tambahan, $key, $default);
    }
}
