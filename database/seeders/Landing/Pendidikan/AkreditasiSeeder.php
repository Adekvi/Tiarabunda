<?php

namespace Database\Seeders\Landing\Pendidikan;

use App\Models\Pendidikan\Akreditasi;
use App\Models\Pendidikan\Program\Fakultas;
use App\Models\Pendidikan\Program\Prodi;
use Illuminate\Database\Seeder;

class AkreditasiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan ada Fakultas dulu (karena Prodi butuh fakultas_id)
        // Jika Anda sudah punya seeder fakultas, baris ini bisa disesuaikan.
        $fakultas = Fakultas::firstOrCreate(
            ['jeneng' => 'Fakultas Ilmu Kesehatan'],
            ['tandha' => 'FIK', 'urip' => true]
        );

        // 2. Data Akreditasi & Prodi berdasarkan gambar
        $data = [
            [
                'akreditasi' => [
                    'jeneng' => 'Sertifikat Akreditasi D3 Farmasi',
                    'status_akred' => 'Baik',
                    'bagean' => 'prodi',
                    'katrangan' => 'Berdasarkan Keputusan LAM-PTKes 0573/LAM-PTKes/Akr/Dip/VIII/2024. Berlaku sampai 08 Agustus 2029.',
                    'path_gambar' => 'akreditasi/d3-farmasi.jpg',
                    'urutan' => 1,
                    'urip' => true,
                ],
                'prodi' => [
                    'jeneng' => 'Diploma Tiga Farmasi',
                    'tandha' => 'D3-FARMASI',
                    'undhak' => 'D3',
                ]
            ],
            [
                'akreditasi' => [
                    'jeneng' => 'Sertifikat Akreditasi D3 Kebidanan',
                    'status_akred' => 'Baik Sekali',
                    'bagean' => 'prodi',
                    'katrangan' => 'Berdasarkan Keputusan LAM-PTKes 0331/LAM-PTKes/Akr/Dip/V/2024. Berlaku sampai 29 Mei 2029.',
                    'path_gambar' => 'akreditasi/d3-kebidanan.jpg',
                    'urutan' => 2,
                    'urip' => true,
                ],
                'prodi' => [
                    'jeneng' => 'Diploma Tiga Kebidanan',
                    'tandha' => 'D3-KEBIDANAN',
                    'undhak' => 'D3',
                ]
            ],
            [
                'akreditasi' => [
                    'jeneng' => 'Sertifikat Akreditasi Pendidikan Profesi Bidan',
                    'status_akred' => 'Baik Sekali',
                    'bagean' => 'prodi',
                    'katrangan' => 'Berdasarkan Keputusan LAM-PTKes 0998/LAM-PTKes/Akr/Pro/XI/2024. Berlaku sampai 27 November 2029.',
                    'path_gambar' => 'akreditasi/profesi-bidan.jpg',
                    'urutan' => 3,
                    'urip' => true,
                ],
                'prodi' => [
                    'jeneng' => 'Pendidikan Profesi Bidan',
                    'tandha' => 'PROF-BIDAN',
                    'undhak' => 'Profesi',
                ]
            ],
            [
                'akreditasi' => [
                    'jeneng' => 'Sertifikat Akreditasi Sarjana Terapan Keperawatan Anestesiologi',
                    'status_akred' => 'Baik Sekali',
                    'bagean' => 'prodi',
                    'katrangan' => 'Berdasarkan Keputusan LAM-PTKes 0464/LAM-PTKes/Akr/Dip/III/2025. Berlaku sampai 20 Maret 2030.',
                    'path_gambar' => 'akreditasi/sarjana-anestesi.jpg',
                    'urutan' => 4,
                    'urip' => true,
                ],
                'prodi' => [
                    'jeneng' => 'Sarjana Terapan Keperawatan Anestesiologi',
                    'tandha' => 'S.Tr-Anestesi',
                    'undhak' => 'D4',
                ]
            ],
            [
                'akreditasi' => [
                    'jeneng' => 'Sertifikat Akreditasi Sarjana Terapan Kebidanan',
                    'status_akred' => 'Baik Sekali',
                    'bagean' => 'prodi',
                    'katrangan' => 'Berdasarkan Keputusan LAM-PTKes 0997/LAM-PTKes/Akr/Dip/XI/2024. Berlaku sampai 27 November 2029.',
                    'path_gambar' => 'akreditasi/sarjana-kebidanan.jpg',
                    'urutan' => 5,
                    'urip' => true,
                ],
                'prodi' => [
                    'jeneng' => 'Sarjana Terapan Kebidanan',
                    'tandha' => 'S.Tr-Kebidanan',
                    'undhak' => 'D4',
                ]
            ],
        ];

        foreach ($data as $item) {
            // Buat Akreditasi
            $akreditasi = Akreditasi::create($item['akreditasi']);

            // Buat Prodi dan kaitkan dengan Akreditasi
            Prodi::create([
                'fakultas_id' => $fakultas->id,
                'akreditasi_id' => $akreditasi->id, // <-- Relasi foreign key
                'jeneng' => $item['prodi']['jeneng'],
                'tandha' => $item['prodi']['tandha'],
                'undhak' => $item['prodi']['undhak'],
                'urutan' => $akreditasi->urutan,
                'urip' => true,
            ]);
        }
    }
}
