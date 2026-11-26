<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Lembaga\LpmService;
use Illuminate\Support\Collection;

class LpmPageContent implements PageContentInterface
{
    public function __construct(
        private readonly LpmService $lpmService
    ) {}

    public function getData(?string $slug = null): array
    {
        $items = $this->lpmService->forPage();

        if ($items->isEmpty()) {
            $items = $this->demoItems();
        }

        $selectedLpm = null;
        if (filled($slug)) {
            $selectedLpm = $items->firstWhere('tandha', $slug)
                ?? $this->lpmService->findBySlug($slug);

            abort_if($selectedLpm === null, 404, 'Dokumen atau informasi LPM tidak ditemukan.');
        }

        // Item kategori 'profil' pertama dijadikan highlight utama LPM
        $profilHighlight = $items->firstWhere('kategori', 'profil');
        $otherItems = $profilHighlight
            ? $items->reject(fn($row) => $row->tandha === $profilHighlight->tandha)->values()
            : $items->values();

        return [
            'lpmLoaded'       => true,
            'lpmHighlight'    => $profilHighlight,
            'lpmItems'        => $otherItems,
            'lpmAllItems'     => $items,
            'lpmKategori'     => $this->lpmService->kategoriOptions(),
            'selectedLpm'     => $selectedLpm,
        ];
    }

    private function demoItems(): Collection
    {
        return collect([
            (object) [
                'id'             => 0,
                'judhul'         => 'Profil Lembaga Penjaminan Mutu (LPM)',
                'tandha'         => 'profil-lembaga-penjaminan-mutu',
                'kategori'       => 'profil',
                'kategori_label' => 'Profil & Struktur LPM',
                'isi'            => '<p>Lembaga Penjaminan Mutu (LPM) Universitas Tiara Bunda bertugas merencanakan, melaksanakan, mengevaluasi, mengendalikan, dan meningkatkan Sistem Penjaminan Mutu Internal (SPMI) secara berkelanjutan di seluruh unit akademik maupun non-akademik.</p>',
                'data_tambahan'  => [
                    'nomor_dokumen' => 'SK-LPM/UTB/001',
                    'tahun'         => '2026',
                    'siklus'        => 'PPEPP Berkelanjutan',
                    'lokasi'        => 'Gedung Rektorat Kampus A, Kota Depok',
                ],
                'has_file'       => false,
                'file_url'       => null,
                'file_ext'       => 'PDF',
                'file_size'      => 'PDF',
                'urutan'         => 1,
            ],
            (object) [
                'id'             => 0,
                'judhul'         => 'Kebijakan & Manual Mutu SPMI (Siklus PPEPP)',
                'tandha'         => 'kebijakan-manual-mutu-spmi',
                'kategori'       => 'spmi',
                'kategori_label' => 'Dokumen SPMI (PPEPP)',
                'isi'            => '<p>Pedoman induk pelaksanaan Sistem Penjaminan Mutu Internal yang mencakup tahapan Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan standar pendidikan tinggi sesuai regulasi nasional.</p>',
                'data_tambahan'  => [
                    'nomor_dokumen' => 'DOK-SPMI/01/2026',
                    'tahun'         => '2026',
                    'siklus'        => 'Penetapan & Pelaksanaan',
                ],
                'has_file'       => false,
                'file_url'       => null,
                'file_ext'       => 'PDF',
                'file_size'      => 'PDF',
                'urutan'         => 2,
            ],
            (object) [
                'id'             => 0,
                'judhul'         => 'Laporan Audit Mutu Internal (AMI) Tahunan',
                'tandha'         => 'laporan-audit-mutu-internal',
                'kategori'       => 'ami',
                'kategori_label' => 'Audit Mutu Internal (AMI)',
                'isi'            => '<p>Evaluasi sistematik dan independen terhadap pemenuhan standar mutu program studi dan unit kerja sebagai dasar Rapat Tinjauan Manajemen (RTM).</p>',
                'data_tambahan'  => [
                    'nomor_dokumen' => 'AMI-UTB/2026',
                    'tahun'         => '2026',
                    'siklus'        => 'Evaluasi & Pengendalian',
                ],
                'has_file'       => false,
                'file_url'       => null,
                'file_ext'       => 'PDF',
                'file_size'      => 'PDF',
                'urutan'         => 3,
            ],
        ]);
    }
}