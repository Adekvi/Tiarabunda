<?php

namespace App\Services\Landing\Pendidikan;

use App\Models\Pendidikan\Pmb;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PmbService
{
    private const CACHE_TTL = 600;
    private const CACHE_KEY = 'landing.pmb.forPage.v1'; // ← versi key

    /**
     * Semua jalur PMB aktif — untuk halaman utama PMB.
     *
     * @return Collection<int, Pmb>
     */
    public function forPage(): Collection
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            // Cache sebagai array primitive (bukan objek Eloquent)
            return Pmb::query()
                ->active()
                ->ordered()
                ->get()
                ->map(fn($p) => [
                    'id'           => $p->id,
                    'jeneng'       => $p->jeneng,
                    'tandha'       => $p->tandha,
                    'katrangan'    => $p->katrangan,
                    'syarat'       => $p->syarat,
                    'langkah'      => $p->langkah,
                    'wektu_bukak'  => $p->wektu_bukak?->toDateString(),
                    'wektu_tutup'  => $p->wektu_tutup?->toDateString(),
                    'urutan'       => $p->urutan,
                    'urip'         => $p->urip,
                    'status'       => $p->status,
                    'status_label' => $p->status_label,
                ])
                ->all();
        });

        return collect($rows)->map(fn(array $r) => (object) $r);
    }

    public function findBySlug(string $slug): ?Pmb
    {
        // Jangan cache model utuh
        return Pmb::query()
            ->active()
            ->where('tandha', $slug)
            ->first();
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
