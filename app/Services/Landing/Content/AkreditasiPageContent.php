<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Pendidikan\AkreditasiService;

class AkreditasiPageContent implements PageContentInterface
{
    public function __construct(private AkreditasiService $akreditasiService) {}

    public function getData(?string $slug = null): array
    {
        $akreditasi = $this->akreditasiService->forPage();

        // Fallback demo jika kosong
        if ($akreditasi->isEmpty()) {
            $akreditasi = $this->demoAkreditasi();
        }

        // Kelompokkan berdasarkan bagean
        $institusi = $akreditasi->where('bagean', 'institusi')->values();
        $prodi     = $akreditasi->where('bagean', 'prodi')->values();

        return [
            'akreditasiInstitusi' => $institusi,
            'akreditasiProdi'     => $prodi,
        ];
    }

    private function demoAkreditasi()
    {
        return collect([
            (object) [
                'jeneng'       => 'Universitas Tiara Bunda',
                'status_akred' => 'B',
                'bagean'       => 'institusi',
                'katrangan'    => 'Berdasarkan SK BAN-PT No. 1234/SK/BAN-PT/Akred/PT/2025. Berlaku hingga tahun 2030.',
                'path_gambar'  => null,
            ],
            (object) [
                'jeneng'       => 'D3 Farmasi',
                'status_akred' => 'Baik Sekali',
                'bagean'       => 'prodi',
                'katrangan'    => 'SK BAN-PT No. 5678/SK/BAN-PT/Akred/D3/2024.',
                'path_gambar'  => null,
            ],
            (object) [
                'jeneng'       => 'D4 Kebidanan',
                'status_akred' => 'Baik',
                'bagean'       => 'prodi',
                'katrangan'    => 'SK LAM-PTKes No. 0123/LAM-PTKes/Akr/Dip/2023.',
                'path_gambar'  => null,
            ],
        ]);
    }
}
