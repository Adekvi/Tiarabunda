<?php

namespace App\Services\Landing\Layanan;

use App\Models\Layanan\Kemahasiswaan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class KemahaService
{
    private const CACHE_TTL = 600;
    private const CACHE_KEY = 'landing.kemahasiswaan.forPage.v1';

    public function forPage(): Collection
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Kemahasiswaan::query()
                ->where('urip', true)
                ->orderBy('urutan')
                ->orderBy('id')
                ->get()
                ->map(fn($k) => [
                    'id'             => $k->id,
                    'judhul'         => $k->judhul,
                    'isi'            => $k->isi,
                    'data_tambahan'  => $k->data_tambahan,
                    'urutan'         => $k->urutan,
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
