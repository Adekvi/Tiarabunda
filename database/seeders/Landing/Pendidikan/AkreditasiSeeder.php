<?php

namespace Database\Seeders\Landing\Pendidikan;

use App\Models\Pendidikan\Akreditasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AkreditasiSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('akreditasis')) {
            $this->command?->warn('Tabel akreditasis belum ada.');
            return;
        }

        // Hapus data lama agar bersih saat di-seed ulang
        Akreditasi::query()->forceDelete();

        $basePath = 'akreditasi';

        $rows = [
            // === DATA AKREDITASI INSTITUSI ===
            [
                'cari_prodi'  => null,
                'jeneng'      => 'Sertifikat Akreditasi Institusi Universitas Tiara Bunda',
                'status'      => 'Baik Sekali',
                'bagean'      => 'institusi',
                'file_gambar' => null,
            ],

            [
                'cari_prodi'  => 'Farmasi',
                'jeneng'      => 'Sertifikat Akreditasi D3 Farmasi',
                'status'      => 'B',
                'bagean'      => 'prodi',
                'file_gambar' => 'ak-farmasi.webp',
            ],
            [
                'cari_prodi'  => 'Kebidanan',
                'jeneng'      => 'Sertifikat Akreditasi D3 Kebidanan',
                'status'      => 'Baik Sekali',
                'bagean'      => 'prodi',
                'file_gambar' => 'ak-kebidanan.webp',
            ],
            [
                'cari_prodi'  => 'Pendidikan Profesi Bidan',
                'jeneng'      => 'Sertifikat Akreditasi Profesi Bidan',
                'status'      => 'Baik Sekali',
                'bagean'      => 'prodi',
                'file_gambar' => 'ak-ppbidan.webp',
            ],
            [
                'cari_prodi'  => 'Kebidanan',
                'jeneng'      => 'Sertifikat Akreditasi Sarjana Terapan Kebidanan',
                'status'      => 'Baik Sekali',
                'bagean'      => 'prodi',
                'file_gambar' => 'ak-stkanes.webp',
            ],
            [
                'cari_prodi'  => 'Keperawatan Bidan Anestesiologi',
                'jeneng'      => 'Sertifikat Akreditasi Sarjana Terapan Keperawatan Anestesiologi',
                'status'      => 'Baik Sekali',
                'bagean'      => 'prodi',
                'file_gambar' => 'ak-stkebidanan.webp',
            ],
        ];

        foreach ($rows as $i => $row) {
            Akreditasi::query()->create([
                'jeneng'       => $row['jeneng'],
                'status_akred' => $row['status'],
                'bagean'       => $row['bagean'],
                'katrangan'    => $row['cari_prodi'] ? 'Akreditasi program studi terkait: ' . $row['cari_prodi'] . '.' : 'Akreditasi tingkat universitas.',
                'path_gambar'  => $basePath . '/' . $row['file_gambar'],
                'urutan'       => $i + 1,
                'urip'         => true,
            ]);
        }

        $this->command?->info('AkreditasiSeeder: ' . count($rows) . ' data berhasil diisi.');
    }
}
