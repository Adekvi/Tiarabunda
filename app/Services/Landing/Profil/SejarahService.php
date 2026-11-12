<?php

namespace App\Services\Landing\Profil;

use App\Models\Profil\Sejarah;
use Illuminate\Support\Facades\Cache;

class SejarahService
{
    private const CACHE_KEY = 'landing.sejarah.active';
    private const CACHE_TTL = 600;

    /**
     * Ambil 1 entri sejarah aktif untuk halaman publik.
     */
    public function forPage(): ?Sejarah
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Sejarah::query()
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
        });
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
