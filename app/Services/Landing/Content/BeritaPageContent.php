<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Informasi\BeritaService;

class BeritaPageContent implements PageContentInterface
{
    public function __construct(private BeritaService $beritaService) {}

    public function getData(?string $slug = null): array
    {
        $berita = $this->beritaService->forPage();

        if ($berita->isEmpty()) {
            $berita = $this->demoBerita();
        }

        $selectedBerita = null;

        // Jika ada slug (halaman detail)
        if ($slug) {
            $selectedBerita = $berita->firstWhere('tandha', $slug);
            abort_if($selectedBerita === null, 404, 'Berita tidak ditemukan.');
        }

        return [
            'beritaList'     => $berita,
            'selectedBerita' => $selectedBerita,
        ];
    }

    private function demoBerita()
    {
        return collect([
            (object) [
                'id'           => 1,
                'judhul'       => 'PKKMB Politeknik Tiara Bunda 2026',
                'tandha'       => 'pkkmb-2026',
                'golongan'     => 'Kampus',
                'ringkesan'    => 'Politeknik Tiara Bunda resmi membuka PKKMB 2026 dengan tema "From New Student to Future Professional".',
                'isi'          => '<p>Depok, Selasa, 15 September 2026 — Politeknik Tiara Bunda resmi membuka kegiatan Pengenalan Kehidupan Kampus bagi Mahasiswa Baru (PKKMB) Tahun 2026...</p>',
                'thumb_url'    => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=600&q=80',
                'sampul_url'   => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1200&q=80',
                'wektu_terbit' => '2026-09-15',
            ],
            (object) [
                'id'           => 2,
                'judhul'       => 'Visitasi Tempat Uji Kompetensi Keperawatan Anestesiologi',
                'tandha'       => 'visitasi-tuk',
                'golongan'     => 'Akademik',
                'ringkesan'    => 'Politeknik Tiara Bunda melaksanakan Visitasi Tempat Uji Kompetensi (TUK) Program Studi Sarjana Terapan Keperawatan Anestesiologi.',
                'isi'          => '<p>Depok, 11 September 2026 — Politeknik Tiara Bunda melaksanakan kegiatan Visitasi Tempat Uji Kompetensi (TUK)...</p>',
                'thumb_url'    => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=600&q=80',
                'sampul_url'   => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=1200&q=80',
                'wektu_terbit' => '2026-09-11',
            ],
        ]);
    }
}
