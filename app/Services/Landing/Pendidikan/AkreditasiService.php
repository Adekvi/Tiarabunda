<?php

namespace App\Services\Landing\Pendidikan;

use App\Models\Pendidikan\Akreditasi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class AkreditasiService
{
    private const CACHE_TTL = 600;
    private const CACHE_KEY = 'landing.akreditasi.forPage.v1';

    public function forPage(): Collection
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Akreditasi::query()
                ->where('urip', true)
                ->orderBy('urutan')
                ->orderBy('id')
                ->get()
                ->map(fn($a) => [
                    'id'           => $a->id,
                    'jeneng'       => $a->jeneng,
                    'status_akred' => $a->status_akred,
                    'bagean'       => $a->bagean,
                    'katrangan'    => $a->katrangan,
                    'path_gambar'  => $a->path_gambar,
                    'urutan'       => $a->urutan,
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
