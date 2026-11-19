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
        if ($beasiswa->isEmpty()) {
            $beasiswa = $this->demoBeasiswa();
        }

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

    private function demoBeasiswa()
    {
        return collect([
            (object) [
                'jeneng'    => 'Beasiswa Prestasi Akademik',
                'tandha'    => 'beasiswa-prestasi',
                'katrangan' => '<p>Beasiswa yang diberikan kepada calon mahasiswa baru dengan nilai rapor semester 1-5 yang sangat memuaskan.</p>',
                'syarat'    => '<ul><li>Nilai rapor rata-rata minimal 85</li><li>Memiliki sertifikat kejuaraan minimal tingkat kabupaten</li><li>Mengisi formulir pendaftaran beasiswa</li></ul>',
                'jangkepan' => 'Potongan UKT 100% selama 1 semester (dapat diperpanjang dengan syarat IPK).',
            ],
            (object) [
                'jeneng'    => 'Beasiswa KIP Kuliah',
                'tandha'    => 'kip-kuliah',
                'katrangan' => '<p>Bantuan biaya pendidikan dari pemerintah bagi mahasiswa dari keluarga ekonomi kurang mampu.</p>',
                'syarat'    => '<ul><li>Memiliki Kartu Indonesia Pintar (KIP) yang aktif</li><li>Lolos seleksi verifikasi ekonomi</li><li>Tidak sedang menerima beasiswa lain</li></ul>',
                'jangkepan' => 'Bebas biaya kuliah (UKT) dan bantuan biaya hidup bulanan dari pemerintah.',
            ],
        ]);
    }
}
