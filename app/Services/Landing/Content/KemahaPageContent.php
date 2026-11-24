<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Layanan\KemahaService;
use App\Services\Landing\Pendidikan\BeasiswaService;
use Illuminate\Support\Str;

class KemahaPageContent implements PageContentInterface
{
    public function __construct(
        private KemahaService $kemahasiswaanService,
        private BeasiswaService $beasiswaService
    ) {}

    public function getData(?string $slug = null): array
    {
        $kemahasiswaan = $this->kemahasiswaanService->forPage();
        if ($kemahasiswaan->isEmpty()) {
            $kemahasiswaan = $this->demoKemahasiswaan();
        }

        $beasiswa = $this->beasiswaService->forPage();

        $selectedKemahasiswaan = null;
        $showBeasiswaList = false;
        $selectedBeasiswa = null;

        if ($slug) {
            if ($slug === 'beasiswa') {
                $showBeasiswaList = true;
            } elseif (Str::startsWith($slug, 'beasiswa/')) {
                $beasiswaSlug = Str::after($slug, 'beasiswa/');
                $selectedBeasiswa = $beasiswa->firstWhere('tandha', $beasiswaSlug)
                    ?? $this->beasiswaService->findBySlug($beasiswaSlug);

                abort_if($selectedBeasiswa === null, 404, 'Program beasiswa tidak ditemukan.');
            } else {
                $selectedKemahasiswaan = $kemahasiswaan->firstWhere('tandha', $slug);

                abort_if($selectedKemahasiswaan === null, 404, 'Informasi kemahasiswaan tidak ditemukan.');
            }
        }

        return [
            'kemahasiswaanHighlight'       => $kemahasiswaan->first(),
            'kemahasiswaanItems'           => $kemahasiswaan->slice(1)->values(),
            'kemahasiswaanBeasiswaInfo'    => $kemahasiswaan->firstWhere('is_beasiswa', true),
            'selectedKemahasiswaan'        => $selectedKemahasiswaan,
            'showKemahasiswaanBeasiswa'    => $showBeasiswaList,
            'kemahasiswaanBeasiswaList'    => $beasiswa,
            'selectedKemahasiswaanBeasiswa' => $selectedBeasiswa,
        ];
    }

    private function demoKemahasiswaan()
    {
        return collect([
            (object) [
                'judhul'        => 'Biro Kemahasiswaan & Alumni',
                'tandha'        => 'biro-kemahasiswaan-alumni',
                'is_beasiswa'   => false,
                'isi'           => '<p>Biro yang mengelola layanan kemahasiswaan, beasiswa internal/eksternal, organisasi mahasiswa, dan hubungan alumni.</p>',
                'data_tambahan' => ['ikon' => 'users', 'warna' => 'blue'],
            ],
            (object) [
                'judhul'        => 'Informasi & Layanan Beasiswa',
                'tandha'        => 'beasiswa',
                'is_beasiswa'   => true,
                'isi'           => '<p>Fasilitasi dan pendampingan pengajuan berbagai program beasiswa internal maupun eksternal bagi mahasiswa.</p>',
                'data_tambahan' => ['tipe' => 'beasiswa', 'ikon' => 'graduation-cap', 'warna' => 'orange'],
            ],
        ]);
    }
}
