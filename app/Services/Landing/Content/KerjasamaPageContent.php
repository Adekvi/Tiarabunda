<?php

namespace App\Services\Landing\Content;

use App\Models\Layanan\Kerjasama;
use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Layanan\KerjasamaService;

class KerjasamaPageContent implements PageContentInterface
{
    public function __construct(
        private readonly KerjasamaService $kerjasamaService
    ) {}

    public function getData(?string $slug = null): array
    {
        $all = $this->kerjasamaService->forPage();

        $selectedKerjasama = null;
        if (filled($slug)) {
            $selectedKerjasama = $all->firstWhere('tandha', $slug);
            abort_if($selectedKerjasama === null, 404, 'Data kerjasama tidak ditemukan.');
        }

        $mitraList = $all
            ->where('jenis', Kerjasama::JENIS_MITRA)
            ->values();

        $penawaranList = $all
            ->where('jenis', Kerjasama::JENIS_PENAWARAN)
            ->values();

        $kategoriList = $mitraList
            ->pluck('kategori')
            ->filter()
            ->unique()
            ->values()
            ->all();

        return [
            'kerjasamaLoaded'    => true,
            'kerjasamaMitra'     => $mitraList,
            'kerjasamaPenawaran' => $penawaranList,
            'kerjasamaKategori'  => $kategoriList,
            'selectedKerjasama'  => $selectedKerjasama,
            'kerjasamaStats'     => [
                'total'     => $all->count(),
                'mitra'     => $mitraList->count(),
                'penawaran' => $penawaranList->count(),
                'kategori'  => count($kategoriList),
            ],
        ];
    }
}
