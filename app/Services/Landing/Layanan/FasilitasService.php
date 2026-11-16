<?php

namespace App\Services\Landing\Layanan;

use App\Models\Layanan\Fasilitas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FasilitasService
{
    private const CACHE_TTL = 600;
    private const CACHE_KEY = 'landing.fasilitas.forPage.v2'; 

    public function forPage(): Collection
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Fasilitas::query()
                ->where('urip', true)
                ->orderBy('urutan')
                ->orderBy('id')
                ->get()
                ->map(fn($f) => [
                    'id'        => $f->id,
                    'jeneng'    => $f->jeneng,
                    'tandha'    => $f->tandha,
                    'katrangan' => $f->katrangan,
                    'thumb_url' => $f->thumb_url,
                    'gambar_url' => $f->gambar_url,
                    'urutan'    => $f->urutan,
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
