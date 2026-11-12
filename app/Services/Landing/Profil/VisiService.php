<?php

namespace App\Services\Landing\Profil;

use App\Models\Profil\Visimisi;
use Illuminate\Support\Facades\Cache;

class VisiService
{
    private const CACHE_KEY = 'landing.visimisi.active';
    private const CACHE_TTL = 600;

    public function forPage(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $rows = Visimisi::query()
                ->active()
                ->ordered()
                ->select([
                    'id',
                    'bagean',
                    'judhul',
                    'isi',
                    'dhaptar',
                    'urutan',
                    'urip',
                ])
                ->get();

            return [
                'visi' => $rows->where('bagean', 'visi')->values(),
                'misi' => $rows->where('bagean', 'misi')->values(),
                'lain' => $rows->whereNotIn('bagean', ['visi', 'misi'])->values(),
            ];
        });
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
