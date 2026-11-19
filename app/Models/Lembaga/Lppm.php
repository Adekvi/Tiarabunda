<?php

namespace App\Models\Lembaga;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Lppm extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

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
    }
}
