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
                ->map(function ($fak) {
                    return [
                        'id'           => $fak->id,
                        'jeneng'       => $fak->jeneng,
                        'tandha'       => $fak->tandha,
                        'kodhe'        => $fak->kodhe,
                        'katrangan'    => $fak->katrangan,
                        'urutan'       => $fak->urutan,
                        'urip'         => $fak->urip,
                        'activeProdis' => $fak->activeProdis->map(function ($prodi) {
                            return [
                                'id'            => $prodi->id,
                                'fakultas_id'   => $prodi->fakultas_id,
                                'akreditasi_id' => $prodi->akreditasi_id,
                                'jeneng'        => $prodi->jeneng,
                                'tandha'        => $prodi->tandha,
                                'undhak'        => $prodi->undhak,
                                'katrangan'     => $prodi->katrangan,
                                'urutan'        => $prodi->urutan,
                                'urip'          => $prodi->urip,
                                'akreditasi'    => ($prodi->akreditasi && $prodi->akreditasi->urip) ? [
                                    'id'           => $prodi->akreditasi->id,
                                    'jeneng'       => $prodi->akreditasi->jeneng,
                                    'status_akred' => $prodi->akreditasi->status_akred,
                                    'bagean'       => $prodi->akreditasi->bagean,
                                    'gambar_url'   => $prodi->akreditasi->gambar_url,
                                ] : null,
                            ];
                        })->values()->all(),
                    ];
                })
                ->values()
                ->all();
        });

        return collect($data)->map(function ($item) {
            $item['activeProdis'] = collect($item['activeProdis'] ?? [])->map(function ($p) {
                if (! empty($p['akreditasi'])) {
                    $p['akreditasi'] = (object) $p['akreditasi'];
                }
                return (object) $p;
            });

            return (object) $item;
        });
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
