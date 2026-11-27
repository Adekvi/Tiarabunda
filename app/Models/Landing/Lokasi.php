<?php

namespace App\Models\Landing;

use App\Models\Concerns\HasUripState;
use App\Services\Landing\LandingHomeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Lokasi extends Model
{
    use HasUripState;
    use SoftDeletes;

    protected $fillable = [
        'kode',
        'badge',
        'nama',
        'deskripsi',
        'alamat',
        'jam',
        'telepon',
        'koordinat',
        'akses',
        'unggulan',
        'foto',
        'map_embed',
        'map_url',
        'urutan',
        'urip',
    ];

    protected $casts = [
        'unggulan' => 'array',
        'urutan'   => 'integer',
        'urip'     => 'boolean',
    ];

    protected static function booted(): void
    {
        $flushCache = static function (): void {
            app(LandingHomeService::class)->forgetLokasi();
        };

        static::saved($flushCache);
        static::deleted($flushCache);
        static::restored($flushCache);
    }

    /**
     * Resolve URL foto dari public/ atau storage/app/public/
     */
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

    /**
     * Format data agar kompatibel dengan prop :items pada <x-landing.section.lokasi />
     *
     * @return array<string, mixed>
     */
    public function toLandingArray(): array
    {
        $allowedTags = '<p><br><strong><b><em><i><u><ul><ol><li><a>';

        return [
            'kode'      => (string) $this->kode,
            'badge'     => (string) $this->badge,
            'nama'      => (string) $this->nama,
            'deskripsi' => strip_tags((string) $this->deskripsi, $allowedTags),
            'alamat'    => (string) $this->alamat,
            'jam'       => (string) $this->jam,
            'telepon'   => (string) $this->telepon,
            'koordinat' => (string) ($this->koordinat ?? ''),
            'akses'     => (string) ($this->akses ?? ''),
            'unggulan'  => is_array($this->unggulan) ? array_values($this->unggulan) : [],
            'foto'      => $this->foto_url,
            'img'       => $this->foto_url,
            'map_embed' => (string) ($this->map_embed ?? ''),
            'map_url'   => (string) ($this->map_url ?? ''),
        ];
    }

    /**
     * Format data untuk sinkronisasi ke config('landing.site.kontak.alamat') (Footer / Halaman Kontak)
     *
     * @return array<string, mixed>
     */
    public function toKontakAlamatArray(): array
    {
        return [
            'label'     => 'Kampus ' . $this->kode,
            'isi'       => (string) $this->alamat,
            'maps'      => (string) ($this->map_url ?? ''),
            'map_embed' => (string) ($this->map_embed ?? ''),
            'jam'       => (string) $this->jam,
            'telp'      => (string) $this->telepon,
        ];
    }
}
