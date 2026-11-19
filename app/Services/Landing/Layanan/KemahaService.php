<?php

namespace App\Services\Landing\Layanan;

use App\Models\Layanan\Kemahasiswaan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class KemahaService
{
    private const CACHE_TTL = 600;
    private const CACHE_KEY = 'landing.kemahasiswaan.forPage.v2';

    public function forPage(): Collection
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Kemahasiswaan::query()
                ->where('urip', true)
                ->orderBy('urutan')
                ->orderBy('id')
                ->get()
                ->map(function ($k) {
                    $meta = $k->data_tambahan ?? [];
                    $isBeasiswa = ($meta['tipe'] ?? null) === 'beasiswa'
                        || Str::contains(Str::lower($k->judhul ?? ''), 'beasiswa');

                    return [
                        'id'            => $k->id,
                        'judhul'        => $k->judhul,
                        'tandha'        => $isBeasiswa
                            ? 'beasiswa'
                            : Str::slug($k->judhul ?? 'item-' . $k->id),
                        'is_beasiswa'   => $isBeasiswa,
                        'isi'           => $k->isi,
                        'data_tambahan' => $meta,
                        'urutan'        => $k->urutan,
                    ];
                })
                ->all();
        });

        return collect($rows)->map(fn(array $r) => (object) $r);
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
