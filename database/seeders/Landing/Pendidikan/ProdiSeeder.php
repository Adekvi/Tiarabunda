<?php

namespace Database\Seeders\Landing\Pendidikan;

use App\Models\Pendidikan\Akreditasi;
use App\Models\Pendidikan\Program\Fakultas;
use App\Models\Pendidikan\Program\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        $fakultasNames = [
            'Ilmu Kesehatan',
            'Keguruan dan Ilmu Pendidikan',
            'Ilmu Komputer',
            'Ekonomi Bisnis',
        ];

        $fakultasMap = [];
        foreach ($fakultasNames as $i => $nama) {
            $fakultasMap[$i] = Fakultas::query()
                ->where('tandha', Str::slug($nama))
                ->firstOrFail();
        }

        /* 
         * AMBIL ID AKREDITASI YANG SUDAH DIBUAT OLEH AkreditasiSeeder
         * Jika tidak ditemukan, nilainya null.
         */
        $akrFarmasi    = Akreditasi::where('jeneng', 'Sertifikat Akreditasi D3 Farmasi')->value('id');
        $akrKebidananD3 = Akreditasi::where('jeneng', 'Sertifikat Akreditasi D3 Kebidanan')->value('id');
        $akrKebidananD4 = Akreditasi::where('jeneng', 'Sertifikat Akreditasi Sarjana Terapan Kebidanan')->value('id');
        $akrAnestesi   = Akreditasi::where('jeneng', 'Sertifikat Akreditasi Sarjana Terapan Keperawatan Anestesiologi')->value('id');
        $akrProfesi    = Akreditasi::where('jeneng', 'Sertifikat Akreditasi Profesi Bidan')->value('id');

        // Untuk prodi lain yang belum ada sertifikat spesifiknya, kita beri null atau akreditasi institusi
        // $akrDefault = Akreditasi::where('jeneng', 'Sertifikat Akreditasi Institusi Universitas Tiara Bunda')->value('id');

        $prodis = [
            // === Fakultas Ilmu Kesehatan (0) ===
            ['jeneng' => 'Farmasi',                         'undhak' => 'D3', 'fakultas' => 0, 'akreditasi_id' => $akrFarmasi],
            ['jeneng' => 'Kebidanan',                       'undhak' => 'D3', 'fakultas' => 0, 'akreditasi_id' => $akrKebidananD3],
            ['jeneng' => 'Keperawatan Bidan Anestesiologi', 'undhak' => 'D4', 'fakultas' => 0, 'akreditasi_id' => $akrAnestesi],
            ['jeneng' => 'Kebidanan',                       'undhak' => 'D4', 'fakultas' => 0, 'akreditasi_id' => $akrKebidananD4],
            ['jeneng' => 'Pendidikan Profesi Bidan',        'undhak' => null, 'fakultas' => 0, 'akreditasi_id' => $akrProfesi],

            // === Fakultas Keguruan dan Ilmu Pendidikan (1) ===
            ['jeneng' => 'Pendidikan Guru PAUD',                 'undhak' => 'D3', 'fakultas' => 1, 'akreditasi_id' => null],
            ['jeneng' => 'Pendidikan Guru Sekolah Dasar (PGSD)', 'undhak' => 'D3', 'fakultas' => 1, 'akreditasi_id' => null],

            // === Fakultas Ilmu Komputer (2) ===
            ['jeneng' => 'Teknologi Perangkat Lunak', 'undhak' => 'D4', 'fakultas' => 2, 'akreditasi_id' => null],
            ['jeneng' => 'Sistem Informasi',          'undhak' => 'S1', 'fakultas' => 2, 'akreditasi_id' => null],
            ['jeneng' => 'Kecerdasan Buatan',         'undhak' => 'S1', 'fakultas' => 2, 'akreditasi_id' => null],
            ['jeneng' => 'Ilmu Komputer',             'undhak' => 'S1', 'fakultas' => 2, 'akreditasi_id' => null],

            // === Fakultas Ekonomi Bisnis (3) ===
            ['jeneng' => 'Logistik Bisnis', 'undhak' => 'D4', 'fakultas' => 3, 'akreditasi_id' => null],
            ['jeneng' => 'Bisnis Digital',  'undhak' => 'D4', 'fakultas' => 3, 'akreditasi_id' => null],
            ['jeneng' => 'Manajemen',       'undhak' => 'S1', 'fakultas' => 3, 'akreditasi_id' => null],
        ];

        foreach ($prodis as $i => $p) {
            $fakultas = $fakultasMap[$p['fakultas']];

            $tandha = Str::slug(($p['undhak'] ?? 'profesi') . '-' . $p['jeneng']);

            Prodi::query()->updateOrCreate(
                [
                    'tandha' => $tandha,
                ],
                [
                    'fakultas_id'   => $fakultas->id,
                    'jeneng'        => $p['jeneng'],
                    'undhak'        => $p['undhak'],
                    'akreditasi_id' => $p['akreditasi_id'],
                    'urutan'        => $i + 1,
                    'urip'          => true,
                ]
            );
        }
    }
}
