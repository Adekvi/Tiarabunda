<?php

namespace Database\Seeders\Landing\Pendidikan;

use App\Models\Pendidikan\Beasiswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BeasiswaSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * Struktur data:
         * - jeneng     : nama beasiswa
         * - tandha     : slug (unique)
         * - katrangan  : HTML (deskripsi umum)
         * - syarat     : HTML (daftar persyaratan)
         * - jangkepan  : ringkasan cakupan
         * - urutan     : int
         * - urip       : bool
         */
        $items = [
            // 1. KIP Kuliah
            [
                'jeneng'    => 'KIP Kuliah',
                'tandha'    => 'kip-kuliah',
                'jangkepan' => 'Bebas UKT + Bantuan Biaya Hidup',
                'katrangan' => '<p>Program <strong>Kartu Indonesia Pintar (KIP) Kuliah</strong> merupakan bantuan pendidikan dari pemerintah untuk calon mahasiswa dari keluarga kurang mampu yang memiliki potensi akademik baik.</p>',
                'syarat'    => '<ul><li>Lulusan SMA/SMK/MA sederajat tahun berjalan atau maksimal 2 tahun sebelumnya.</li><li>Memiliki KIP atau terdaftar dalam DTKS (Data Terpadu Kesejahteraan Sosial).</li><li>Memiliki potensi akademik yang baik, dibuktikan dengan rapor atau prestasi.</li><li>Tidak sedang menerima beasiswa dari sumber lain.</li></ul>',
                'urutan'    => 1,
                'urip'      => true,
            ],

            // 2. Beasiswa Prestasi Akademik
            [
                'jeneng'    => 'Beasiswa Prestasi Akademik',
                'tandha'    => 'beasiswa-prestasi-akademik',
                'jangkepan' => 'Potongan UKT 50% - 100%',
                'katrangan' => '<p>Beasiswa ini diberikan kepada mahasiswa baru yang memiliki nilai akademik sangat baik dan berprestasi di tingkat sekolah maupun nasional.</p>',
                'syarat'    => '<ol><li>Nilai rata-rata rapor semester 1-5 minimal <strong>85</strong>.</li><li>Memiliki sertifikat prestasi akademik (OSN, KSN, atau lomba sejenis).</li><li>Lolos seleksi wawancara dan verifikasi dokumen.</li><li>Bersedia mempertahankan IPK minimal 3.25 selama masa studi.</li></ol>',
                'urutan'    => 2,
                'urip'      => true,
            ],

            // 3. Beasiswa Prestasi Non-Akademik
            [
                'jeneng'    => 'Beasiswa Prestasi Non-Akademik',
                'tandha'    => 'beasiswa-prestasi-non-akademik',
                'jangkepan' => 'Potongan UKT 25% - 75%',
                'katrangan' => '<p>Ditujukan bagi calon mahasiswa yang memiliki prestasi luar biasa di bidang olahraga, seni, atau organisasi.</p>',
                'syarat'    => '<ul><li>Memiliki sertifikat kejuaraan tingkat kabupaten/kota, provinsi, atau nasional.</li><li>Usia maksimal 22 tahun pada saat pendaftaran.</li><li>Melampirkan portofolio atau dokumentasi prestasi.</li></ul>',
                'urutan'    => 3,
                'urip'      => true,
            ],

            // 4. Beasiswa Alumni (Keluarga Alumni)
            [
                'jeneng'    => 'Beasiswa Keluarga Alumni',
                'tandha'    => 'beasiswa-keluarga-alumni',
                'jangkepan' => 'Potongan Biaya Registrasi',
                'katrangan' => '<p>Program keringanan khusus bagi putra/putri dari alumni Universitas Tiara Bunda yang ingin melanjutkan pendidikan di kampus yang sama.</p>',
                'syarat'    => '<p>Syarat pengajuan:</p><ul><li>Melampirkan ijazah orang tua yang merupakan alumni Universitas Tiara Bunda.</li><li>Mengisi formulir keringanan biaya.</li><li>Verifikasi dokumen oleh bagian keuangan.</li></ul>',
                'urutan'    => 4,
                'urip'      => true,
            ],

            // 5. Beasiswa Atlet Berprestasi (Nonaktif)
            [
                'jeneng'    => 'Beasiswa Atlet Berprestasi',
                'tandha'    => 'beasiswa-atlet-berprestasi',
                'jangkepan' => 'Bebas UKT 1 Tahun Pertama',
                'katrangan' => '<p>Beasiswa khusus bagi atlet yang telah mengharumkan nama daerah atau negara. <em>Program ini sedang ditinjau ulang dan dinonaktifkan sementara.</em></p>',
                'syarat'    => '<ul><li>Atlet aktif yang dibina oleh KONI atau organisasi resmi.</li><li>Memiliki medali emas/perak/brnonze di tingkat nasional.</li></ul>',
                'urutan'    => 5,
                'urip'      => false, // Dinonaktifkan
            ],
        ];

        foreach ($items as $item) {
            Beasiswa::withTrashed()
                ->where('tandha', $item['tandha'])
                ->forceDelete();

            Beasiswa::query()->create([
                'jeneng'    => $item['jeneng'],
                'tandha'    => $item['tandha'] ?: Str::slug($item['jeneng']),
                'katrangan' => $item['katrangan'],
                'syarat'    => $item['syarat'],
                'jangkepan' => $item['jangkepan'],
                'urutan'    => (int) ($item['urutan'] ?? 0),
                'urip'      => (bool) ($item['urip'] ?? true),
            ]);
        }

        $this->command?->info('✔ ' . count($items) . ' data beasiswa berhasil di-seed.');
        $this->command?->newLine();

        $this->command?->table(
            ['#', 'Nama Beasiswa', 'Cakupan', 'Aktif'],
            collect($items)->values()->map(fn($it, $i) => [
                $i + 1,
                $it['jeneng'],
                $it['jangkepan'],
                $it['urip'] ? 'Ya' : 'Tidak',
            ])->all()
        );
    }
}
