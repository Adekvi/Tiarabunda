<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Layanan\PerpusService;

class PerpusPageContent implements PageContentInterface
{
    public function __construct(private PerpusService $perpusService) {}

    public function getData(?string $slug = null): array
    {
        $perpus = $this->perpusService->forPage();

        if ($perpus->isEmpty()) {
            $perpus = $this->demoPerpus();
        }

        $selectedPerpus = null;

        if ($slug) {
            $selectedPerpus = $perpus->first(function ($item) use ($slug) {
                return data_get($item, 'tandha') === $slug
                    || data_get($item, 'data_tambahan.fakultas_slug') === $slug;
            });

            abort_if($selectedPerpus === null, 404, 'Halaman perpustakaan fakultas tidak ditemukan.');
        }

        return [
            'perpusHighlight' => $perpus->first(),
            'perpusItems'     => $perpus->slice(1)->values(),
            'selectedPerpus'  => $selectedPerpus,
        ];
    }

    private function demoPerpus()
    {
        return collect([
            (object) [
                'judhul'        => 'Perpustakaan Pusat Universitas Tiara Bunda',
                'tandha'        => 'perpustakaan-pusat',
                'isi'           => '<p>Perpustakaan pusat menyediakan berbagai koleksi buku, jurnal, dan ruang baca yang nyaman untuk mendukung kegiatan belajar mahasiswa.</p>',
                'data_tambahan' => ['ikon' => 'book-open', 'warna' => 'blue', 'url_portal' => PerpusService::DEFAULT_PORTAL_URL],
            ],
        ]);
    }
}
