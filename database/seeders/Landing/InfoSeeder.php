<?php

namespace Database\Seeders\Landing;

use App\Models\Landing\Info;
use Illuminate\Database\Seeder;

class InfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Matikan foreign key check (jika ada) & bersihkan tabel agar tidak duplikat
        // \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        // Info::truncate();
        // \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $data = [
            [
                'k'      => 'PMB',
                't'      => 'Pendaftaran Mahasiswa Baru Gelombang 1 Dibuka',
                'd'      => 'Dapatkan potongan biaya pendaftaran bagi pendaftar awal. Tersedia jalur prestasi, reguler, dan beasiswa KIP Kuliah.',
                'tgl'    => '2026-10-20',
                'img'    => 'info/brosur-pmb-2026.webp',
                'urip'   => true,
                'urutan' => 1,
            ],
            [
                'k'      => 'Pengumuman',
                't'      => 'Jadwal Ujian Tengah Semester (UTS) Ganjil',
                'd'      => 'Mahasiswa diharapkan mengecek jadwal masing-masing di portal akademik. Pastikan tidak ada tunggakan biaya administrasi.',
                'tgl'    => '12 - 18 Nov 2026',
                'img'    => 'info/jadwal-uts.webp',
                'urip'   => true,
                'urutan' => 2,
            ],
            [
                'k'      => 'Prestasi',
                't'      => 'Mahasiswa Kebidanan Juara 1 Lomba Inovasi Kesehatan',
                'd'      => 'Selamat kepada tim mahasiswa yang telah mengharumkan nama kampus di tingkat nasional. Terus berinovasi!',
                'tgl'    => '05 Okt 2026',
                'img'    => 'info/prestasi-mahasiswa.webp',
                'urip'   => true,
                'urutan' => 3,
            ],
            [
                'k'      => 'Urgensi',
                't'      => 'Pemeliharaan Jaringan Server Kampus',
                'd'      => 'Layanan portal akademik akan dimatikan sementara pada pukul 22.00 - 23.59 WIB untuk pemeliharaan rutin.',
                'tgl'    => 'Hari ini, 22.00 WIB',
                'img'    => 'info/maintenance-server.webp',
                'urip'   => true,
                'urutan' => 4,
            ],
            [
                'k'      => 'SK',
                't'      => 'SK Perpanjangan Akreditasi Program Studi',
                'd'      => 'Berdasarkan surat keputusan terbaru, program studi kita berhasil mempertahankan akreditasi unggul.',
                'tgl'    => '2026-08-10',
                'img'    => 'info/sk-akreditasi.webp',
                'urip'   => true,
                'urutan' => 5,
            ],
            [
                'k'      => 'Draf Internal',
                't'      => 'Rencana Kerja Sama Dengan Rumah Sakit Mitra',
                'd'      => 'Dokumen ini masih dalam tahap draf dan tidak akan muncul di landing page karena status urip = false.',
                'tgl'    => '20 Nov 2026',
                'img'    => 'info/draf-kerjasama.webp',
                'urip'   => false,
                'urutan' => 6,
            ],
        ];

        foreach ($data as $item) {
            Info::create($item);
        }
    }
}
