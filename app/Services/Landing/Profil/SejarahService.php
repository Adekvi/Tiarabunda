<?php

namespace App\Services\Landing\Profil;

use App\Models\Profil\Sejarah;
use Illuminate\Support\Facades\Cache;

class SejarahService
{
    private const CACHE_KEY = 'landing.sejarah.active.v2'; // Naikkan versi cache
    private const CACHE_TTL = 600;

    /**
     * Ambil 1 entri sejarah aktif untuk halaman publik.
     */
    public function forPage(): ?object
    {
        $data = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $sejarah = Sejarah::query()
                ->active()
                ->ordered()
                ->select([
                    'id',
                    'judhul',
                    'isi',
                    'kronologi',
                    'path_sampul',
                    'urutan',
                    'urip',
                ])
                ->first();

            if (! $sejarah) {
                return null;
            }

            return [
                'judhul'      => $sejarah->judhul,
                'isi'         => $sejarah->isi,
                'kronologi'   => $sejarah->kronologi,
                'path_sampul' => $sejarah->path_sampul,
            ];
        });

        return $data ? (object) $data : null;
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
