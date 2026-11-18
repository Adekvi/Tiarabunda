<?php

namespace Database\Seeders\Landing\Pendidikan;

use App\Models\Pendidikan\Akreditasi;
use Illuminate\Database\Seeder;

class AkreditasiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'jeneng' => 'Sertifikat Akreditasi D3 Farmasi',
                'status_akred' => 'Baik',
                'bagean' => 'prodi',
                'katrangan' => 'Berdasarkan Keputusan LAM-PTKes 0573/LAM-PTKes/Akr/Dip/VIII/2024. Berlaku sampai 08 Agustus 2029.',
                'path_gambar' => 'akreditasi/d3-farmasi.jpg',
                'urutan' => 1,
                'urip' => true,
            ],
            [
                'jeneng' => 'Sertifikat Akreditasi D3 Kebidanan',
                'status_akred' => 'Baik Sekali',
                'bagean' => 'prodi',
                'katrangan' => 'Berdasarkan Keputusan LAM-PTKes 0331/LAM-PTKes/Akr/Dip/V/2024. Berlaku sampai 29 Mei 2029.',
                'path_gambar' => 'akreditasi/d3-kebidanan.jpg',
                'urutan' => 2,
                'urip' => true,
            ],
            [
                'jeneng' => 'Sertifikat Akreditasi Pendidikan Profesi Bidan',
                'status_akred' => 'Baik Sekali',
                'bagean' => 'prodi',
                'katrangan' => 'Berdasarkan Keputusan LAM-PTKes 0998/LAM-PTKes/Akr/Pro/XI/2024. Berlaku sampai 27 November 2029.',
                'path_gambar' => 'akreditasi/profesi-bidan.jpg',
                'urutan' => 3,
                'urip' => true,
            ],
            [
                'jeneng' => 'Sertifikat Akreditasi Sarjana Terapan Keperawatan Anestesiologi',
                'status_akred' => 'Baik Sekali',
                'bagean' => 'prodi',
                'katrangan' => 'Berdasarkan Keputusan LAM-PTKes 0464/LAM-PTKes/Akr/Dip/III/2025. Berlaku sampai 20 Maret 2030.',
                'path_gambar' => 'akreditasi/sarjana-anestesi.jpg',
                'urutan' => 4,
                'urip' => true,
            ],
            [
                'jeneng' => 'Sertifikat Akreditasi Sarjana Terapan Kebidanan',
                'status_akred' => 'Baik Sekali',
                'bagean' => 'prodi',
                'katrangan' => 'Berdasarkan Keputusan LAM-PTKes 0997/LAM-PTKes/Akr/Dip/XI/2024. Berlaku sampai 27 November 2029.',
                'path_gambar' => 'akreditasi/sarjana-kebidanan.jpg',
                'urutan' => 5,
                'urip' => true,
            ],
        ];

        foreach ($data as $item) {
            Akreditasi::query()->updateOrCreate(
                ['jeneng' => $item['jeneng']],
                $item
            );
        }
    }
}
