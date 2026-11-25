<?php

namespace App\Models\Lembaga;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Lpm extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    public const CACHE_KEY = 'landing.lembaga.lpm.forPage.v1';

    protected $table = 'lpms';

    public const KATEGORI = [
        'profil'     => 'Profil & Struktur LPM',
        'spmi'       => 'Dokumen SPMI (PPEPP)',
        'ami'        => 'Audit Mutu Internal (AMI)',
        'akreditasi' => 'Akreditasi Institusi & Prodi',
        'monev'      => 'Survei Kepuasan & Monev',
    ];

    protected $fillable = [
        'judhul',
        'tandha',
        'kategori',
        'isi',
        'data_tambahan',
        'path_file',
        'nama_file_asli',
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
        static::saving(function (Lpm $model) {
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

    public function getResolvedFilePathAttribute(): ?string
    {
        if (blank($this->path_file)) {
            return null;
        }

        $relative = ltrim($this->path_file, '/');

        $inPublic = public_path($relative);
        if (is_file($inPublic)) {
            return $inPublic;
        }

        $inStorage = storage_path('app/public/' . $relative);
        if (is_file($inStorage)) {
            return $inStorage;
        }

        return null;
    }

    public function getFileExistsAttribute(): bool
    {
        return $this->resolved_file_path !== null;
    }

    public function getFileExtensionAttribute(): string
    {
        $file = $this->path_file ?: $this->nama_file_asli ?: 'pdf';
        $ext  = pathinfo($file, PATHINFO_EXTENSION);

        return strtoupper($ext ?: 'PDF');
    }

    public function getFileSizeAttribute(): string
    {
        $fullPath = $this->resolved_file_path;
        if (! $fullPath) {
            return $this->file_extension;
        }

        $bytes = filesize($fullPath) ?: 0;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }

        return max(1, (int) round($bytes / 1024)) . ' KB';
    }
}
