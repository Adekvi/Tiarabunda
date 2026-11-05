<?php

namespace App\Services\Landing;

use App\Models\Informasi\Berita;
use App\Models\Informasi\Faq;
use App\Models\Layanan\Fasilitas;
use App\Models\Pendidikan\Program\Fakultas;
use App\Models\Pendidikan\Program\Prodi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class LandingHomeService
{
    private const TTL = 300;

    public function forHome(): array
    {
        return [
            'fakultas_prodi'   => $this->fakultasProdi(),
            'fasilitas' => $this->fasilitas(),
            'berita' => $this->beritas(),
            'faq' => $this->faqs(),
        ];
    }

    public function fakultasProdi(): array
    {
        $rows = Cache::remember('landing.home.fakultas_prodi', self::TTL, function () {
            $fakultas = Fakultas::query()
                ->select(['id', 'jeneng', 'tandha'])
                ->active()
                ->ordered()
                ->whereHas('activeProdis') // hanya fakultas yang punya prodi aktif
                ->get();

            $prodi = Prodi::query()
                ->select(['id', 'fakultas_id', 'jeneng', 'tandha', 'undhak', 'urutan'])
                ->active()
                ->ordered()
                ->whereIn('fakultas_id', $fakultas->pluck('id'))
                ->get();

            // map fakultas_id → index array agar cocok dengan pola view ($fakultas[$p['fakultas']])
            $fakIndex = $fakultas->values()->mapWithKeys(
                fn($f, $i) => [$f->id => $i]
            );

            return [
                'fakultas' => $fakultas->pluck('jeneng')->values()->all(),
                'prodi'    => $prodi->map(fn($p) => [
                    'fakultas' => $fakIndex[$p->fakultas_id] ?? null,
                    'jenjang'  => $p->undhak,
                    'nama'     => $p->jeneng,
                    'tandha'   => $p->tandha,
                ])->filter(fn($p) => $p['fakultas'] !== null)->values()->all(),
            ];
        });

        if (! is_array($rows)) {
            Cache::forget('landing.home.fakultas_prodi');
            $rows = ['fakultas' => [], 'prodi' => []];
        }

        return $rows;
    }


    public function fasilitas(int $limit = 8): Collection
    {
        $rows = Cache::remember('landing.home.fasilitas', self::TTL, function () use ($limit) {
            return Fasilitas::query()
                ->select([
                    'id',
                    'jeneng',
                    'tandha',
                    'katrangan',
                    'path_gambar',
                    'path_thumb',
                    'urutan',
                    'urip',
                ])
                ->active()        // ← hanya urip = true
                ->ordered()       // ← urut sesuai master
                ->limit($limit)
                ->get()
                ->map(function ($f) {
                    /** @var Fasilitas $f */

                    return [
                        'nama'      => $f->jeneng ?? '',
                        'tandha'    => $f->tandha,
                        'katrangan' => $f->katrangan ?? '',
                        'img'       => $f->thumb_url ?? $f->gambar_url,  // accessor dari model
                        'urutan'    => $f->urutan,
                    ];
                })
                ->values()
                ->all(); // ← array murni, konsisten dengan pola faq/berita
        });

        if (! is_array($rows)) {
            Cache::forget('landing.home.fasilitas');
            $rows = [];
        }

        return collect($rows);
    }

    public function beritas(int $limit = 4): Collection
    {
        $rows = Cache::remember('landing.home.berita', self::TTL, function () use ($limit) {
            return Berita::query()
                ->select([
                    'id',
                    'judhul',
                    'tandha',
                    'golongan',
                    'path_sampul',
                    'path_thumb',
                    'wektu_terbit',
                    'urutan',
                    'urip',
                ])
                ->terbit()
                ->orderByDesc('wektu_terbit')
                ->orderBy('urutan')
                ->limit($limit)
                ->get()
                ->map(function ($b) {
                    // /** @var \App\Models\Informasi\Berita $b */ //
                    return [
                        'j' => Berita::GOLONGAN[$b->golongan] ?? 'Berita',
                        't' => $b->judhul ?? '',
                        'tgl' => $b->wektu_terbit?->translatedFormat('d M Y') ?? '',
                        'img' => $b->thumb_url ?? $b->sampul_url,
                        'url' => $b->tandha
                            ? url('/informasi/berita-terbaru/' . $b->tandha)
                            : url('/informasi/berita-terbaru'),
                    ];
                })
                ->values()
                ->all(); // <-- array murni
        });

        if (! is_array($rows)) {
            Cache::forget('landing.home.berita');
            $rows = [];
        }

        return collect($rows);
    }

    public function faqs(): Collection
    {
        // Simpan ARRAY di cache, bukan Collection
        $rows = Cache::remember('landing.home.faq', self::TTL, function () {
            return Faq::query()
                ->select(['id', 'pitakon', 'wangsulan', 'urutan'])
                ->active()
                ->ordered()
                ->limit(20)
                ->get()
                ->map(fn($f) => [
                    $f->pitakon,
                    $f->wangsulan,
                ])
                ->values()
                ->all();
        });

        // Guard: cache lama rusak / incomplete
        if (! is_array($rows)) {
            Cache::forget('landing.home.faq');
            $rows = [];
        }

        return collect($rows);
    }

    public function forgetFakultasProdi(): void
    {
        Cache::forget('landing.home.fakultas_prodi');
    }

    public function forgetFasilitas(): void
    {
        Cache::forget('landing.home.fasilitas');
    }

    public function forgetBerita(): void
    {
        Cache::forget('landing.home.berita');
    }

    public function forgetFaq(): void
    {
        Cache::forget('landing.home.faq');
    }

    public function forgetHome(): void
    {
        $this->forgetFakultasProdi();
        $this->forgetFaq();
        $this->forgetBerita();
        $this->forgetFasilitas();
    }
}
