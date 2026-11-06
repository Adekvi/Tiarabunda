<?php

namespace Database\Seeders\Landing\Profil;

use App\Models\Profil\Pimpinan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PimpinanSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan direktori penyimpanan foto siap
        if (!Storage::disk('public')->exists('pimpinan')) {
            Storage::disk('public')->makeDirectory('pimpinan');
        }

        $items = [
            [
                'jeneng'      => 'Dr. Bdn. Lusy Pratiwi, S.Tr.Keb., M.K.M.',
                'kalungguhan' => 'Direktur Universitas Tiara Bunda',
                'path_foto'   => 'pimpinan/direktur.webp',
                'unen'        => '<p>Sebagai Direktur, saya bangga memimpin kampus Universitas Tiara Bunda yang berkomitmen untuk memberikan pendidikan berkualitas dan berorientasi pada kebutuhan industri dan masyarakat.</p><p>Universitas Tiara Bunda sebagai Perguruan Tinggi Vokasi mencetak lulusan siap kerja dan akan menghantarkan hingga pintu gerbang kesuksesan. Kami berharap dapat menjalin kerja sama yang baik dengan seluruh stakeholder di Universitas Tiara Bunda dan masyarakat luas untuk meraih prestasi yang lebih tinggi.</p>',
                'riwayat'     => "1. Pendidikan Sarjana Terapan Kebidanan\n2. Pendidikan Magister Kesehatan Masyarakat\n3. Praktisi dan Dosen di Bidang Kebidanan",
                'urutan'      => 1,
                'urip'        => true,
            ],
            // Anda bisa menambahkan data pimpinan lainnya di sini (misal: Wakil Direktur, Dekan, dll.)
            // [
            //     'jeneng'      => 'Nama Wakil Direktur',
            //     'kalungguhan' => 'Wakil Direktur I',
            //     'path_foto'   => 'pimpinan/wakil-direktur.jpg',
            //     'unen'        => '<p>Sambutan singkat wakil direktur...</p>',
            //     'riwayat'     => "...",
            //     'urutan'      => 2,
            //     'urip'        => true,
            // ],
        ];

        foreach ($items as $item) {
            // Hapus data lama dengan nama yang sama (termasuk yang di-soft delete) agar tidak duplicate
            Pimpinan::withTrashed()
                ->where('jeneng', $item['jeneng'])
                ->forceDelete();

            // Buat data baru
            Pimpinan::query()->create([
                'jeneng'      => $item['jeneng'],
                'kalungguhan' => $item['kalungguhan'],
                'path_foto'   => $item['path_foto'],
                'unen'        => $item['unen'],
                'riwayat'     => $item['riwayat'],
                'urutan'      => (int) $item['urutan'],
                'urip'        => (bool) $item['urip'],
            ]);
        }

        $this->command?->info('✔ ' . count($items) . ' data pimpinan berhasil di-seed.');
        $this->command?->newLine();

        $this->command?->table(
            ['#', 'Nama', 'Jabatan', 'Foto'],
            collect($items)->values()->map(fn($it, $i) => [
                $i + 1,
                $it['jeneng'],
                $it['kalungguhan'],
                $it['path_foto'] ? 'Ada' : 'Tidak',
            ])->all()
        );
    }
}
