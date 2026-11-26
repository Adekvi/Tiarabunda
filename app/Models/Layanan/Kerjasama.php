<?php

namespace App\Models\Layanan;

use App\Models\Concerns\HasUripState;
use App\Models\Concerns\HasUrutan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Kerjasama extends Model
{
    use HasUripState, HasUrutan, SoftDeletes;

    public const CACHE_KEY = 'landing.layanan.kerjasama.forPage.v1';

    public const JENIS_MITRA     = 'mitra';
    public const JENIS_PENAWARAN = 'penawaran';

    protected $table = 'kerjasamas';

    protected $fillable = [
        'jenis',
        'tandha',
        'kategori',
        'nomor_dokumen',
        'periode',
        'katrangan',
        'logo',
        'path_file',
        'nama_file_asli',
        'tautan',
        'kontak_pic',
        'urutan',
        'urip',
    ];

    protected static function booted(): void
    {
        $flush = fn() => Cache::forget(self::CACHE_KEY);

        static::saved($flush);
        static::deleted($flush);
        static::restored($flush);
    }

    protected function casts(): array
    {
        return [
            'urip'   => 'boolean',
            'urutan' => 'integer',
        ];
    }

    public function getJenisLabelAttribute(): string
    {
        return $this->jenis === self::JENIS_PENAWARAN
            ? 'Penawaran Kerjasama'
            : 'Mitra Instansi / Perusahaan';
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (blank($this->logo)) {
            return null;
        }

        $relative = ltrim($this->logo, '/');

        if (is_file(public_path($relative))) {
            return asset($relative);
        }

        if (is_file(storage_path('app/public/' . $relative))) {
            return asset('storage/' . $relative);
        }

        return null;
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
