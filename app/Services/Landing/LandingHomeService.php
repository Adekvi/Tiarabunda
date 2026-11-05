<?php

namespace App\Services\Landing;

use App\Models\Informasi\Berita;
use App\Models\Informasi\Faq;
use App\Models\Layanan\Fasilitas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class LandingHomeService
{
    private const TTL = 300;

    public function forHome(): array
    {
        return [
            'faq' => $this->faqs(),
            'berita' => $this->beritas(),
            'fasilitas' => $this->fasilitas(),
        ];
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

    public function forgetFaq(): void
    {
        Cache::forget('landing.home.faq');
    }

    public function forgetBerita(): void
    {
        Cache::forget('landing.home.berita');
    }

    public function forgetFasilitas(): void
    {
        Cache::forget('landing.home.fasilitas');
    }

    public function forgetHome(): void
    {
        $this->forgetFaq();
        $this->forgetBerita();
        $this->forgetFasilitas();
    }
}
