<?php

namespace App\Services\Landing\Layanan;

use App\Models\Layanan\Perpus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PerpusService
{
    private const CACHE_TTL = 600;
    private const CACHE_KEY = 'landing.perpus.forPage.v1';

    public function forPage(): Collection
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Perpus::query()
                ->where('urip', true)
                ->orderBy('urutan')
                ->orderBy('id')
                ->get()
                ->map(fn($p) => [
                    'id'             => $p->id,
                    'judhul'         => $p->judhul,
                    'isi'            => $p->isi,
                    'data_tambahan'  => $p->data_tambahan,
                    'urutan'         => $p->urutan,
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
