<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Lembaga\LppmService;

class LppmPageContent implements PageContentInterface
{
    public function __construct(private LppmService $lppmService) {}

    public function getData(?string $slug = null): array
    {
        $items = $this->lppmService->forPage();

        if ($items->isEmpty()) {
            $items = $this->demoLppm();
        }

        $selectedLppm = null;
        $isPortalProgress = false;

        if ($slug === 'portal') {
            $isPortalProgress = true;
        } elseif ($slug) {
            $selectedLppm = $items->firstWhere('tandha', $slug);
            abort_if($selectedLppm === null, 404, 'Informasi program LPPM tidak ditemukan.');
        }

        $highlight = $items->firstWhere('kategori', 'profil') ?? $items->first();
        $programs  = $items->reject(
            fn($item) => data_get($item, 'tandha') === data_get($highlight, 'tandha')
        )->values();

        return [
            'lppmHighlight'        => $highlight,
            'lppmItems'            => $programs,
            'selectedLppm'         => $selectedLppm,
            'isLppmPortalProgress' => $isPortalProgress,
        ];
    }

    private function demoLppm()
    {
        return collect([
            (object) [
                'judhul'         => 'Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)',
                'tandha'         => 'profil-lppm',
                'kategori'       => 'profil',
                'kategori_label' => 'Profil & Layanan LPPM',
                'isi'            => '<p>LPPM mengoordinasikan kegiatan penelitian, pengabdian kepada masyarakat, publikasi ilmiah, dan pengelolaan HKI di lingkungan kampus.</p>',
                'data_tambahan'  => [
                    'ikon'       => 'award',
                    'warna'      => 'blue',
                    'url_portal' => LppmService::PORTAL_PROGRESS_PATH,
                ],
            ],
            (object) [
                'judhul'         => 'Penelitian & Hibah Internal',
                'tandha'         => 'penelitian-hibah-internal',
                'kategori'       => 'penelitian',
                'kategori_label' => 'Penelitian & Hibah',
                'isi'            => '<p>Fasilitasi pendanaan penelitian dosen pemula, penelitian terapan, dan kolaborasi riset mahasiswa.</p>',
                'data_tambahan'  => ['ikon' => 'microscope', 'warna' => 'blue'],
            ],
            (object) [
                'judhul'         => 'Pengabdian kepada Masyarakat (PkM)',
                'tandha'         => 'pengabdian-masyarakat',
                'kategori'       => 'pengabdian',
                'kategori_label' => 'Pengabdian kepada Masyarakat',
                'isi'            => '<p>Program pemberdayaan masyarakat, desa binaan, dan pendampingan UMKM berbasis keilmuan kampus.</p>',
                'data_tambahan'  => ['ikon' => 'users', 'warna' => 'green'],
            ],
        ]);
    }
}
