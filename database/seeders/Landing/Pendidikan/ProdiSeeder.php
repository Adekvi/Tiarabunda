<?php

namespace Database\Seeders\Landing\Pendidikan;

use App\Models\Pendidikan\Program\Fakultas;
use App\Models\Pendidikan\Program\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        // Samakan urutan dengan FakultasSeeder
        $fakultasNames = [
            'Ilmu Kesehatan',
            'Keguruan dan Ilmu Pendidikan',
            'Ilmu Komputer',
            'Ekonomi Bisnis',
        ];

        // Lookup fakultas by tandha (aman terhadap ID)
        $fakultasMap = [];
        foreach ($fakultasNames as $i => $nama) {
            $fakultasMap[$i] = Fakultas::query()
                ->where('tandha', Str::slug($nama))
                ->firstOrFail();
        }

        /*
         * Data prodi.
         * Catatan: kolom `gelar` TIDAK ada di tabel `prodis`,
         * jadi gelar disimpan di komentar untuk referensi saja.
         * Kalau nanti perlu, tambah kolom `gelar` via migration.
         *
         * Field:
         *  - jeneng   => nama prodi
         *  - undhak   => jenjang (D3/D4/S1/null)
         *  - akred    => akreditasi (opsional, isi manual)
         *  - katrangan=> deskripsi (opsional)
         *  - fakultas => index fakultas (0..3)
         */
        $prodis = [
            // === Fakultas Ilmu Kesehatan (0) ===
            ['jeneng' => 'Farmasi',                         'undhak' => 'D3', 'gelar' => 'A.Md.Farm', 'fakultas' => 0],
            ['jeneng' => 'Kebidanan',                       'undhak' => 'D3', 'gelar' => 'A.Md.Keb',  'fakultas' => 0],
            ['jeneng' => 'Keperawatan Bidan Anestesiologi', 'undhak' => 'D4', 'gelar' => 'S.Tr.Kes',  'fakultas' => 0],
            ['jeneng' => 'Kebidanan',                       'undhak' => 'D4', 'gelar' => 'S.Tr.Keb',  'fakultas' => 0],
            ['jeneng' => 'Pendidikan Profesi Bidan',        'undhak' => null, 'gelar' => 'Bdn',       'fakultas' => 0],

            // === Fakultas Keguruan dan Ilmu Pendidikan (1) ===
            ['jeneng' => 'Pendidikan Guru PAUD',                 'undhak' => 'D3', 'gelar' => 'S.Pd', 'fakultas' => 1],
            ['jeneng' => 'Pendidikan Guru Sekolah Dasar (PGSD)', 'undhak' => 'D3', 'gelar' => 'S.Pd', 'fakultas' => 1],

            // === Fakultas Ilmu Komputer (2) ===
            ['jeneng' => 'Teknologi Perangkat Lunak', 'undhak' => 'D4', 'gelar' => 'S.Tr.Kom', 'fakultas' => 2],
            ['jeneng' => 'Sistem Informasi',          'undhak' => 'S1', 'gelar' => 'S.Kom',    'fakultas' => 2],
            ['jeneng' => 'Kecerdasan Buatan',         'undhak' => 'S1', 'gelar' => 'S.Kom',    'fakultas' => 2],
            ['jeneng' => 'Ilmu Komputer',             'undhak' => 'S1', 'gelar' => 'S.Kom',    'fakultas' => 2],

            // === Fakultas Ekonomi Bisnis (3) ===
            ['jeneng' => 'Logistik Bisnis', 'undhak' => 'D4', 'gelar' => 'S.Tr.Log', 'fakultas' => 3],
            ['jeneng' => 'Bisnis Digital',  'undhak' => 'D4', 'gelar' => 'S.Tr.Bns', 'fakultas' => 3],
            ['jeneng' => 'Manajemen',       'undhak' => 'S1', 'gelar' => 'S.M',      'fakultas' => 3],
        ];

        foreach ($prodis as $i => $p) {
            $fakultas = $fakultasMap[$p['fakultas']];

            // tandha unik: undhak + jeneng  (solusi nama dobel "Kebidanan")
            $tandha = Str::slug(($p['undhak'] ?? 'profesi') . '-' . $p['jeneng']);

            Prodi::query()->updateOrCreate(
                [
                    'fakultas_id' => $fakultas->id,
                    'tandha'      => $tandha,
                ],
                [
                    'jeneng'    => $p['jeneng'],
                    'undhak'    => $p['undhak'],
                    'katrangan' => null,          // isi manual bila perlu
                    'akred'     => null,          // isi manual bila perlu
                    'urutan'    => $i + 1,
                    'urip'      => true,
                ]
            );
        }
    }
}
