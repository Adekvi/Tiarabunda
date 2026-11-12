<?php

namespace App\Services\Landing\Pendidikan;

use App\Models\Pendidikan\Program\Fakultas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProgramService
{
    private const CACHE_KEY = 'landing.program-pendidikan';
    private const CACHE_TTL = 600;

    /**
     * Untuk Halaman (menggunakan relasi activeProdis)
     */
    public function forPage(): Collection
    {
        // 1. Ambil dari cache dalam bentuk Array (bukan Model)
        $data = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Fakultas::query()
                ->active()
                ->ordered()
                ->select(['id', 'jeneng', 'tandha', 'kodhe', 'katrangan', 'urutan', 'urip'])
                ->with([
                    'activeProdis' => fn($q) => $q->select([
                        'id',
                        'fakultas_id',
                        'akreditasi_id',
                        'jeneng',
                        'tandha',
                        'undhak',
                        'katrangan',
                        'urutan',
                        'urip',
                    ]),
                    'activeProdis.akreditasi:id,jeneng,status_akred,bagean,path_gambar,urip',
                ])
                ->get()
                ->toArray();
        });

        return collect($data)->map(fn($item) => (object) $item);
    }

    /**
     * Untuk Navbar (menggunakan relasi prodis standar)
     */
    public function getFakultasWithProdi(): Collection
    {
        $data = Cache::remember('nav_fakultas_prodi', 3600, function () {
            return Fakultas::query()
                ->where('urip', true)
                ->whereNull('deleted_at')
                ->with(['prodis' => function ($query) {
                    $query->where('urip', true)
                        ->whereNull('deleted_at')
                        ->orderBy('urutan', 'asc');
                }])
                ->orderBy('urutan', 'asc')
                ->get()
                ->toArray();
        });

        return collect($data)->map(function ($item) {
            // Ubah prodis menjadi object juga
            $item['prodis'] = collect($item['prodis'] ?? [])->map(fn($p) => (object) $p);
            return (object) $item;
        });
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget('nav_fakultas_prodi');
    }
}
