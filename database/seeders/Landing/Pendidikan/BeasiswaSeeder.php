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
            [
                'jeneng'    => 'KIP Kuliah',
                'tandha'    => 'kip-kuliah',
                'jangkepan' => 'Bebas UKT + Bantuan Biaya Hidup',
                'katrangan' => '<p>Program <strong>Kartu Indonesia Pintar (KIP) Kuliah</strong> merupakan bantuan pendidikan dari pemerintah untuk calon mahasiswa dari keluarga kurang mampu yang memiliki potensi akademik baik.</p>',
                'syarat'    => '<ul><li>Lulusan SMA/SMK/MA sederajat tahun berjalan atau maksimal 2 tahun sebelumnya.</li><li>Memiliki KIP atau terdaftar dalam DTKS (Data Terpadu Kesejahteraan Sosial).</li><li>Memiliki potensi akademik yang baik, dibuktikan dengan rapor atau prestasi.</li><li>Tidak sedang menerima beasiswa dari sumber lain.</li></ul>',
                'urutan'    => 1,
                'urip'      => true,
            ],

            [
                'jeneng'    => 'Beasiswa Reguler',
                'tandha'    => 'beasiswa-reguler',
                'jangkepan' => 'Potongan Biaya Pendidikan (UKT)',
                'katrangan' => '<p>Program <strong>Beasiswa Reguler</strong> merupakan keringanan biaya pendidikan dari institusi bagi calon mahasiswa baru yang mendaftar melalui jalur reguler dan memenuhi kriteria seleksi akademik maupun administrasi.</p>',
                'syarat'    => '<ul><li>Lulusan SMA/SMK/MA sederajat atau Paket C.</li><li>Melengkapi berkas pendaftaran mahasiswa baru jalur reguler.</li><li>Memiliki nilai rapor rata-rata yang memenuhi standar kelulusan seleksi.</li><li>Mengikuti dan lulus tahapan seleksi masuk yang diselenggarakan oleh panitia PMB.</li></ul>',
                'urutan'    => 2,
                'urip'      => true,
            ],
        ];

        Beasiswa::withTrashed()
            ->whereIn('tandha', [
                ...collect($items)->pluck('tandha')->all(),
                'beasiswa-atlet-berprestasi',
            ])
            ->forceDelete();

        foreach ($items as $item) {
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
