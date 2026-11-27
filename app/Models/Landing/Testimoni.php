<?php

namespace App\Models\Landing;

use App\Models\Concerns\HasUripState;
use App\Services\Landing\LandingHomeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Testimoni extends Model
{
    use HasUripState;
    use SoftDeletes;

    public const KATEGORI = [
        'alumni'    => 'Alumni Berkarier',
        'mahasiswa' => 'Mahasiswa Aktif',
        'beasiswa'  => 'Penerima Beasiswa',
    ];

    protected $fillable = [
        'nama',
        'peran',
        'angkatan',
        'kategori',
        'instansi',
        'sorotan',
        'kutipan',
        'rating',
        'foto',
        'urutan',
        'urip',
    ];

    protected $casts = [
        'rating' => 'integer',
        'urutan' => 'integer',
        'urip'   => 'boolean',
    ];

    protected static function booted(): void
    {
        $flushCache = static function (): void {
            app(LandingHomeService::class)->forgetTestimoni();
        };

        static::saved($flushCache);
        static::deleted($flushCache);
        static::restored($flushCache);
        static::forceDeleted($flushCache);
    }

    public function getFotoUrlAttribute(): string
    {
        $src = trim((string) ($this->foto ?? ''));
        if ($src === '') {
            return '';
        }

        if (str_starts_with($src, 'data:') || str_starts_with($src, 'http') || str_starts_with($src, '/')) {
            return $src;
        }

        $clean = ltrim($src, '/');

        if (file_exists(public_path($clean))) {
            return asset($clean);
        }

        if (Storage::disk('public')->exists($clean)) {
            return asset('storage/' . $clean);
        }

        return asset($clean);
    }

    public function getInisialAttribute(): string
    {
        $clean = trim((string) preg_replace('/\b(Ns|Dr|Hj|H|Prof)\.|,.*$/u', '', (string) ($this->nama ?? '')));
        $parts = preg_split('/\s+/', $clean, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        if ($parts === []) {
            return 'TB';
        }

        $first  = mb_substr($parts[0], 0, 1);
        $second = count($parts) > 1 ? mb_substr($parts[1], 0, 1) : '';

        return mb_strtoupper($first . $second);
    }

    public function getKategoriKeyAttribute(): string
    {
        $kat = strtolower(trim((string) ($this->kategori ?? 'alumni')));

        return array_key_exists($kat, self::KATEGORI) ? $kat : 'alumni';
    }

    public function getKategoriLabelAttribute(): string
    {
        return self::KATEGORI[$this->kategori_key];
    }

    /**
     * Format data untuk komponen <x-landing.section.testi />
     *
     * @return array<string, mixed>
     */
    public function toLandingArray(): array
    {
        return [
            'nama'     => (string) ($this->nama ?: 'Mahasiswa & Alumni'),
            'peran'    => (string) ($this->peran ?: 'Civitas Akademika'),
            'angkatan' => (string) ($this->angkatan ?: 'Terverifikasi'),
            'kategori' => $this->kategori_key,
            'instansi' => (string) ($this->instansi ?? ''),
            'sorotan'  => (string) ($this->sorotan ?: 'Pengalaman Akademik & Praktik'),
            'kutipan'  => trim(strip_tags((string) ($this->kutipan ?? ''))),
            'rating'   => max(1, min(5, (int) ($this->rating ?? 5))),
            'foto'     => $this->foto_url,
            'img'      => $this->foto_url,
        ];
    }
}
