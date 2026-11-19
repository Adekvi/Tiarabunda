<?php

namespace App\Services\Landing\Layanan;

use App\Models\Layanan\Perpus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PerpusService
{
    private const CACHE_TTL = 600;
    private const CACHE_KEY = 'landing.perpus.forPage.v3';
    public const DEFAULT_PORTAL_URL = 'https://perpus.poltektiarabundadepok.ac.id/';

    public function forPage(): Collection
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Perpus::query()
                ->where('urip', true)
                ->orderBy('urutan')
                ->orderBy('id')
                ->get()
                ->map(function ($p) {
                    $meta = $p->data_tambahan ?? [];

                    if (! empty($meta['url_portal'])) {
                        $meta['url_portal'] = self::normalizePortalUrl($meta['url_portal']);
                    }

                    $tandha = $meta['fakultas_slug']
                        ?? Str::slug($p->judhul ?? 'perpus-' . $p->id);

                    return [
                        'id'            => $p->id,
                        'judhul'        => $p->judhul,
                        'tandha'        => $tandha,
                        'isi'           => $p->isi,
                        'data_tambahan' => $meta,
                        'urutan'        => $p->urutan,
                    ];
                })
                ->all();
        });

        return collect($rows)->map(fn(array $r) => (object) $r);
    }

    public static function normalizePortalUrl(?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        if (Str::contains($url, ['perpus.tiarabunda.ac.id', 'elibrary.tiarabunda.ac.id', 'repository.tiarabunda.ac.id'])) {
            return self::DEFAULT_PORTAL_URL;
        }

        return $url;
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
