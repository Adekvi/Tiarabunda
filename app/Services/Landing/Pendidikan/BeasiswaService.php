<?php

namespace App\Services\Landing\Pendidikan;

use App\Models\Pendidikan\Beasiswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class BeasiswaService
{
    private const CACHE_TTL = 600;
    private const CACHE_KEY = 'landing.beasiswa.forPage.v1';

    public function forPage(): Collection
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Beasiswa::query()
                ->where('urip', true)
                ->orderBy('urutan')
                ->orderBy('id')
                ->get()
                ->map(fn($b) => [
                    'id'           => $b->id,
                    'jeneng'       => $b->jeneng,
                    'tandha'       => $b->tandha,
                    'katrangan'    => $b->katrangan,
                    'syarat'       => $b->syarat,
                    'syarat_list'  => $b->syarat_list,
                    'jangkepan'    => $b->jangkepan,
                    'urutan'       => $b->urutan,
                ])
                ->all();
        });

        return collect($rows)->map(fn(array $r) => (object) $r);
    }

    public function findBySlug(string $slug): ?Beasiswa
    {
        return Beasiswa::query()
            ->where('urip', true)
            ->where('tandha', $slug)
            ->first();
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
