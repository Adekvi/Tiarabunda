<?php

namespace App\Services\Landing\Content;

use App\Models\Profil\Pimpinan;
use App\Services\Landing\Contracts\PageContentInterface;
use Illuminate\Support\Facades\Cache;

class SambutanPageContent implements PageContentInterface
{
    public const CACHE_KEY = 'landing.profil.sambutan';
    private const CACHE_TTL = 600;

    public function getData(?string $prodiSlug = null): array
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Pimpinan::query()
                ->where('urip', true)
                ->orderBy('urutan', 'asc')
                ->orderBy('id', 'asc')
                ->get(['id', 'jeneng', 'kalungguhan', 'path_foto', 'unen', 'riwayat', 'urutan'])
                ->map(function ($item) {
                    $cleanPath = $item->path_foto
                        ? preg_replace('/^(storage|public)\//', '', $item->path_foto)
                        : null;

                    return [
                        'id'          => $item->id,
                        'nama'        => $item->jeneng,
                        'jabatan'     => $item->kalungguhan,
                        'foto_url'    => $cleanPath ? asset('storage/' . $cleanPath) : null,
                        'sambutan'    => $item->unen,
                        'riwayat'     => $item->riwayat,
                    ];
                })
                ->values()
                ->all();
        });

        if (! is_array($rows)) {
            Cache::forget(self::CACHE_KEY);
            $rows = [];
        }

        if ($rows === []) {
            $fallback = config('landing.home.rektor');

            if (is_array($fallback) && ! empty($fallback['nama'])) {
                $rows = [[
                    'id'       => null,
                    'nama'     => $fallback['nama'] ?? '',
                    'jabatan'  => $fallback['jabatan'] ?? 'Rektor',
                    'foto_url' => null,
                    'sambutan' => $fallback['kutipan'] ?? '',
                    'riwayat'  => null,
                ]];
            }
        }

        return [
            'sambutan' => $rows[0] ?? null,
            'jajaran'  => array_slice($rows, 1),
        ];
    }
}
