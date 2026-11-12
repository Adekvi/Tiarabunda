<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Pendidikan\BeasiswaService;

class BeasiswaPageContent implements PageContentInterface
{
    public function __construct(private BeasiswaService $beasiswaService) {}

    public function getData(?string $slug = null): array
    {
        $beasiswa = $this->beasiswaService->forPage();

        // Fallback demo jika kosong
        if ($beasiswa->isEmpty()) {
            $beasiswa = $this->demoBeasiswa();
        }

        $selectedBeasiswa = null;

        if ($slug) {
            $selectedBeasiswa = $beasiswa->firstWhere('tandha', $slug);

            // Fallback jika tidak ada di cache, cari ke DB
            if (! $selectedBeasiswa) {
                $selectedBeasiswa = $this->beasiswaService->findBySlug($slug);
            }

            abort_if($selectedBeasiswa === null, 404, 'Beasiswa tidak ditemukan.');
        }

        return [
            'beasiswaList'    => $beasiswa,
            'selectedBeasiswa' => $selectedBeasiswa,
        ];
    }

    private function demoBeasiswa()
    {
        return collect([
            (object) [
                'jeneng'      => 'Beasiswa Prestasi Akademik',
                'tandha'      => 'beasiswa-prestasi',
                'katrangan'   => 'Beasiswa yang diberikan kepada calon mahasiswa baru dengan nilai rapor semester 1-5 yang sangat memuaskan.',
                'syarat'      => "Nilai rapor rata-rata minimal 85\nMemiliki sertifikat kejuaraan minimal tingkat kabupaten\nMengisi formulir pendaftaran beasiswa",
                'syarat_list' => [
                    'Nilai rapor rata-rata minimal 85',
                    'Memiliki sertifikat kejuaraan minimal tingkat kabupaten',
                    'Mengisi formulir pendaftaran beasiswa',
                ],
                'jangkepan'   => 'Potongan UKT 100% selama 1 semester (dapat diperpanjang dengan syarat IPK).',
            ],
            (object) [
                'jeneng'      => 'Beasiswa KIP Kuliah',
                'tandha'      => 'kip-kuliah',
                'katrangan'   => 'Bantuan biaya pendidikan dari pemerintah bagi mahasiswa dari keluarga ekonomi kurang mampu.',
                'syarat'      => "Memiliki Kartu Indonesia Pintar (KIP) yang aktif\nLolos seleksi verifikasi ekonomi\nTidak sedang menerima beasiswa lain",
                'syarat_list' => [
                    'Memiliki Kartu Indonesia Pintar (KIP) yang aktif',
                    'Lolos seleksi verifikasi ekonomi',
                    'Tidak sedang menerima beasiswa lain',
                ],
                'jangkepan'   => 'Bebas biaya kuliah (UKT) dan bantuan biaya hidup bulanan dari pemerintah.',
            ],
        ]);
    }
}
