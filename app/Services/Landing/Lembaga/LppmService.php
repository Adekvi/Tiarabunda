<?php

namespace App\Services\Landing\Lembaga;

use App\Models\Lembaga\Lppm;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LppmService
{
    private const CACHE_TTL = 600;
    private const CACHE_KEY = 'landing.lppm.forPage.v2';

    public const PORTAL_PROGRESS_PATH  = '/lembaga/lppm/portal';
    public const PORTAL_DISPLAY_DOMAIN = 'https://lppm.poltektiarabundadepok.ac.id/';

    public function forPage(): Collection
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Lppm::query()
                ->where('urip', true)
                ->orderBy('urutan')
                ->orderBy('id')
                ->get()
                ->map(function ($item) {
                    $meta = $item->data_tambahan ?? [];

                    if (! empty($meta['url_portal'])) {
                        $meta['url_portal'] = self::resolvePortalUrl(
                            $meta['url_portal'],
                            $meta['portal_status'] ?? null
                        );
                    }

                    return [
                        'id'             => $item->id,
                        'judhul'         => $item->judhul,
                        'tandha'         => $item->tandha ?: Str::slug($item->judhul ?? 'lppm-' . $item->id),
                        'kategori'       => $item->kategori ?: 'penelitian',
                        'kategori_label' => Lppm::KATEGORI[$item->kategori] ?? 'Program LPPM',
                        'isi'            => $item->isi,
                        'data_tambahan'  => $meta,
                        'urutan'         => $item->urutan,
                    ];
                })
                ->all();
        });

        return collect($rows)->map(fn(array $r) => (object)$r);
    }

    public static function resolvePortalUrl(?string $url, ?string $status = null): ?string
    {
        $url = trim((string)$url);

        if ($url === '') {
            return null;
        }

        if ($status !== 'live' && Str::contains($url, 'lppm.poltektiarabunda')) {
            return self::PORTAL_PROGRESS_PATH;
        }

        return $url;
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
