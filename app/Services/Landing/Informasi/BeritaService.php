<?php

namespace App\Services\Landing\Informasi;

use App\Models\Informasi\Berita;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class BeritaService
{
    private const CACHE_TTL = 600;
    private const CACHE_KEY = 'landing.berita.forPage.v1';

    public function forPage(): Collection
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Berita::query()
                ->terbit()
                ->orderByDesc('wektu_terbit')
                ->get()
                ->map(fn($b) => [
                    'id'           => $b->id,
                    'judhul'       => $b->judhul,
                    'tandha'       => $b->tandha,
                    'golongan'     => $b->golongan,
                    'ringkesan'    => $b->ringkesan,
                    'isi'          => $b->isi,
                    'thumb_url'    => $b->thumb_url,
                    'sampul_url'   => $b->sampul_url,
                    'wektu_terbit' => $b->wektu_terbit?->toDateString(),
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
