<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Layanan\KemahaService;

class KemahaPageContent implements PageContentInterface
{
    public function __construct(private KemahaService $kemahasiswaanService) {}

    public function getData(?string $slug = null): array
    {
        $kemahasiswaan = $this->kemahasiswaanService->forPage();

        // Fallback demo jika kosong
        if ($kemahasiswaan->isEmpty()) {
            $kemahasiswaan = $this->demoKemahasiswaan();
        }

        // Ambil 1 item pertama untuk banner/highlight
        $highlight = $kemahasiswaan->first();
        $items     = $kemahasiswaan->slice(1)->values();

        return [
            'kemahasiswaanHighlight' => $highlight,
            'kemahasiswaanItems'     => $items,
        ];
    }

    private function demoKemahasiswaan()
    {
        return collect([
            (object) [
                'judhul' => 'Badan Eksekutif Mahasiswa (BEM)',
                'isi'    => '<p>BEM adalah lembaga tertinggi kemahasiswaan di tingkat universitas yang berfungsi sebagai wadah aspirasi dan penggerak kegiatan mahasiswa.</p>',
                'data_tambahan' => ['ikon' => 'users', 'warna' => 'blue'],
            ],
            (object) [
                'judhul' => 'Unit Kegiatan Mahasiswa (UKM)',
                'isi'    => '<ul><li>UKM Olahraga (Futsal, Basket, Voli)</li><li>UKM Seni (Paduan Suara, Tari Tradisional)</li><li>UKM Penalaran (Debat, Karya Tulis Ilmiah)</li><li>UKM Keagamaan</li></ul>',
                'data_tambahan' => ['ikon' => 'star', 'warna' => 'orange'],
            ],
            (object) [
                'judhul' => 'Program Kreativitas Mahasiswa',
                'isi'    => '<p>Wadah bagi mahasiswa untuk mengembangkan ide kreatif dan inovatif melalui penelitian, pengabdian masyarakat, dan kewirausahaan.</p>',
                'data_tambahan' => ['ikon' => 'lightbulb', 'warna' => 'green'],
            ],
            (object) [
                'judhul' => 'Layanan Bimbingan Konseling',
                'isi'    => '<p>Layanan psikologis dan konseling gratis untuk membantu mahasiswa mengatasi masalah pribadi, akademik, maupun sosial.</p>',
                'data_tambahan' => ['ikon' => 'heart', 'warna' => 'red'],
            ],
        ]);
    }
}
