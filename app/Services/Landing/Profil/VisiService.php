<?php

namespace App\Services\Landing\Profil;

use App\Models\Profil\Visimisi;
use Illuminate\Support\Facades\Cache;

class VisiService
{
    private const CACHE_KEY = 'landing.visimisi.active.v2';
    private const CACHE_TTL = 600;

    public function forPage(): array
    {
        $data = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $rows = Visimisi::query()
                ->active()
                ->ordered()
                ->select(['id', 'bagean', 'judhul', 'isi', 'dhaptar', 'urutan'])
                ->get();

            return $rows->map(fn($r) => [
                'id'      => $r->id,
                'bagean' => $r->bagean,
                'judhul' => $r->judhul,
                'isi'     => $r->isi,
                'dhaptar' => $r->dhaptar,
            ])->toArray();
        });

        $collection = collect($data)->map(fn($r) => (object) $r);

        return [
            'visi' => $collection->where('bagean', 'visi')->values(),
            'misi' => $collection->where('bagean', 'misi')->values(),
            'lain' => $collection->whereNotIn('bagean', ['visi', 'misi'])->values(),
        ];
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
