<?php

namespace App\Services\Landing\Pendidikan;

use App\Models\Pendidikan\Beasiswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class BeasiswaService
{
    public const CACHE_KEY = 'landing.pendidikan.beasiswa.forPage.v1';
    private const CACHE_TTL = 600;

    public function forPage(): Collection
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Beasiswa::query()
                ->select([
                    'id',
                    'jeneng',
                    'tandha',
                    'katrangan',
                    'syarat',
                    'jangkepan',
                    'urutan',
                    'urip',
                ])
                ->active()
                ->ordered()
                ->get()
                ->map(fn($b) => [
                    'id'          => $b->id,
                    'jeneng'      => $b->jeneng,
                    'tandha'      => $b->tandha,
                    'katrangan'   => $b->katrangan,
                    'syarat'      => $b->syarat,
                    'syarat_list' => $b->syarat_list,
                    'jangkepan'   => $b->jangkepan,
                    'urutan'      => $b->urutan,
                    'urip'        => $b->urip,
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

    public function findBySlug(string $slug): ?object
    {
        $row = Beasiswa::query()
            ->select([
                'id',
                'jeneng',
                'tandha',
                'katrangan',
                'syarat',
                'jangkepan',
                'urutan',
                'urip',
            ])
            ->active()
            ->where('tandha', $slug)
            ->first();

        if (! $row) {
            return null;
        }

        return (object) [
            'id'          => $row->id,
            'jeneng'      => $row->jeneng,
            'tandha'      => $row->tandha,
            'katrangan'   => $row->katrangan,
            'syarat'      => $row->syarat,
            'syarat_list' => $row->syarat_list,
            'jangkepan'   => $row->jangkepan,
            'urutan'      => $row->urutan,
            'urip'        => $row->urip,
        ];
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
