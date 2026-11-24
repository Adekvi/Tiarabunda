<?php

namespace App\Services\Landing\Layanan;

use App\Models\Layanan\Akademik;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class AkademikService
{
    public const CACHE_KEY = 'landing.layanan.akademik.forPage.v2';
    private const CACHE_TTL = 600;

    public function forPage(): Collection
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Akademik::query()
                ->select([
                    'id',
                    'jeneng',
                    'tandha',
                    'kategori',
                    'katrangan',
                    'path_file',
                    'nama_file_asli',
                    'urutan',
                    'urip',
                ])
                ->active()
                ->ordered()
                ->get()
                ->map(fn($d) => [
                    'id'             => $d->id,
                    'jeneng'         => $d->jeneng,
                    'tandha'         => $d->tandha,
                    'kategori'       => $d->kategori ?: 'Umum',
                    'katrangan'      => $d->katrangan,
                    'file_exists'    => $d->file_exists,
                    'file_ext'       => $d->file_extension,
                    'file_size'      => $d->file_size,
                    'nama_file_asli' => $d->nama_file_asli ?: ($d->tandha . '.' . strtolower($d->file_extension)),
                    'download_url'   => route('landing.akademik.download', ['tandha' => $d->tandha]),
                ])
                ->values()
                ->all();
        });

        if (! is_array($rows)) {
            $this->clearCache();
            return collect();
        }

        return collect($rows)->map(fn(array $r) => (object) $r);
    }

    public function findBySlug(string $tandha): ?Akademik
    {
        return Akademik::query()
            ->active()
            ->where('tandha', $tandha)
            ->first();
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
