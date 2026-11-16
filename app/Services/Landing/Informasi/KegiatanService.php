<?php

namespace App\Services\Landing\Informasi;

use App\Models\Informasi\Kegiatan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class KegiatanService
{
    private const CACHE_TTL = 600;
    private const CACHE_KEY = 'landing.kegiatan.forPage.v1';

    public function forPage(): Collection
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Kegiatan::query()
                ->terjadwal()
                ->orderBy('wektu_mulai')
                ->orderBy('urutan')
                ->get()
                ->map(fn($k) => [
                    'id'            => $k->id,
                    'judhul'        => $k->judhul,
                    'tandha'        => $k->tandha,
                    'katrangan'     => $k->katrangan,
                    'papan'         => $k->papan,
                    'wektu_mulai'   => $k->wektu_mulai?->toIso8601String(),
                    'wektu_rampung' => $k->wektu_rampung?->toIso8601String(),
                    'sampul_url'    => $k->sampul_url,
                    'thumb_url'     => $k->thumb_url,
                ])
                ->all();
        });

        return collect($rows)->map(fn(array $r) => (object) $r);
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
