<?php

namespace App\Services\Landing;

use App\Models\Informasi\Berita;
use App\Models\Informasi\Faq;
use App\Models\Layanan\Fasilitas;
use App\Models\Layanan\Perpus;
use App\Models\Pendidikan\Pmb;
use App\Models\Pendidikan\Program\Fakultas;
use App\Models\Pendidikan\Program\Prodi;
use App\Models\Profil\Pimpinan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class LandingHomeService
{
    private const TTL = 300;

    public function forHome(): array
    {
        return [
            'pimpinan'       => $this->pimpinan(),
            'fakultas_prodi'   => $this->fakultasProdi(),
            'jalur'          => $this->jalurPmb(),
            'fasilitas' => $this->fasilitas(),
            'perpus'         => $this->perpus(),
            'berita' => $this->beritas(),
            'faq' => $this->faqs(),
        ];
    }

    public function pimpinan(): ?array
    {
        $row = Cache::remember('landing.home.pimpinan', self::TTL, function () {
            $item = Pimpinan::query()
                ->where('urip', true)
                ->orderBy('urutan', 'asc')
                ->first();

            if (!$item) {
                return null;
            }

            return [
                'nama'     => $item->jeneng,
                'jabatan'  => $item->kalungguhan,
                'kutipan'  => trim(strip_tags($item->unen)),
                'foto_url' => $item->path_foto ? asset('storage/' . $item->path_foto) : null,
            ];
        });

        // Guard cache
        if ($row !== null && !is_array($row)) {
            Cache::forget('landing.home.pimpinan');
            return null;
        }

        return $row;
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

    public function jalurPmb(int $limit = 4): array
    {
        $rows = Cache::remember('landing.home.jalur_pmb', self::TTL, function () use ($limit) {
            $today = now()->startOfDay();

            return Pmb::query()
                ->select([
                    'id',
                    'jeneng',
                    'tandha',
                    'katrangan',
                    'wektu_tutup',
                    'urutan',
                    'urip'
                ])
                // GUNAKAN WHERE EKSPLISIT JIKA TRAIT RAGU
                ->where('urip', true)
                ->where(function ($q) use ($today) {
                    $q->whereNull('wektu_tutup')
                        ->orWhereDate('wektu_tutup', '>=', $today);
                })
                ->ordered()
                ->limit($limit)
                ->get()
                ->map(function ($p) {
                    return [
                        'nama'      => $p->jeneng,
                        'tandha'    => $p->tandha,
                        'deskripsi' => strip_tags($p->katrangan ?? ''),
                    ];
                })
                ->values()
                ->all();
        });

        if (! is_array($rows)) {
            Cache::forget('landing.home.jalur_pmb');
            $rows = [];
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

    public function perpus(int $limit = 6): array
    {
        $rows = Cache::remember('landing.home.perpus', self::TTL, function () use ($limit) {
            return Perpus::query()
                ->select([
                    'id',
                    'judhul',
                    'isi',
                    'data_tambahan',
                    'urutan',
                    'urip',
                ])
                ->active()
                ->ordered()
                ->limit($limit)
                ->get()
                ->map(function ($p) {
                    /** @var Perpus $f */
                    $meta = $p->data_tambahan ?? [];

                    return [
                        'judul'       => $p->judhul ?? '',
                        'deskripsi'   => trim(strip_tags($p->isi ?? '')),
                        'url_portal'  => $meta['url_portal'] ?? null,
                        'jam_layanan' => $meta['jam_layanan'] ?? null,
                        'kontak'      => $meta['kontak'] ?? null,
                        'email'       => $meta['email'] ?? null,
                    ];
                })
                ->values()
                ->all();
        });

        // Guard cache rusak (mis. format lama)
        if (! is_array($rows)) {
            Cache::forget('landing.home.perpus');
            $rows = [];
        }

        return $rows;
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

    public function forgetPimpinan(): void
    {
        Cache::forget('landing.home.pimpinan');
    }

    public function forgetFakultasProdi(): void
    {
        Cache::forget('landing.home.fakultas_prodi');
    }

    public function forgetJalurPmb(): void
    {
        Cache::forget('landing.home.jalur_pmb');
    }

    public function forgetFasilitas(): void
    {
        Cache::forget('landing.home.fasilitas');
    }

    public function forgetPerpus(): void
    {
        Cache::forget('landing.home.perpus');
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
        $this->forgetPimpinan();
        $this->forgetFakultasProdi();
        $this->forgetJalurPmb();
        $this->forgetPerpus();
        $this->forgetFaq();
        $this->forgetBerita();
        $this->forgetFasilitas();
    }
}
