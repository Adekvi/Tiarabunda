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
                't'      => 'Pendaftaran Mahasiswa Baru Dibuka',
                'd'      => 'Dapatkan potongan biaya pendaftaran bagi pendaftar awal. Tersedia jalur prestasi, reguler, dan beasiswa KIP Kuliah.',
                'tgl'    => '2026-10-20',
                'img'    => 'info/brosur-pmb-2026.webp',
                'urip'   => true,
                'urutan' => 1,
            ],
            [
                'k'      => 'Urgensi',
                't'      => 'Selamat dan Sukses Universitas Tiara Bunda',
                'd'      => 'Politeknik Tiara Bunda dan Politeknik Bisnis Digital Indonesia resmi bergabung menjadi Universitas Tiara Bunda berdasarkan Keputusan Kementerian Pendidikan Tinggi, Sains, dan Teknologi Republik Indonesia Nomor: 1229/B/O/2026. ',
                'tgl'    => '21 Okt 2026',
                'img'    => 'info/utb.webp',
                'urip'   => true,
                'urutan' => 2,
            ],
            [
                'k'      => 'Pengumuman',
                't'      => 'Lokasi Kampus B dan Gedung Baru',
                'd'      => 'GEDUNG B POLITEKNIK TIARA BUNDA Kini hadir di lokasi strategis: Jl. Galur No. 8, Krukut, Limo, Kota Depok.',
                'tgl'    => '17 Jun 2026',
                'img'    => 'info/gedung.webp',
                'urip'   => true,
                'urutan' => 3,
            ],
            [
                'k'      => 'Sosial',
                't'      => 'OPEN DONASI PEDULI KARHUTLA - Universitas Tiara Bunda',
                'd'      => 'Saat ini, saudara-saudara kita sedang menghadapi dampak kebakaran hutan dan lahan (karhutla). Mari bersama-sama menumbuhkan kepedulian dengan memberikan bantuan melalui donas.',
                'tgl'    => '27 Okt 2026',
                'img'    => 'info/karhutla.webp',
                'urip'   => true,
                'urutan' => 4,
            ],
            [
                'k'      => 'Pengumuman',
                't'      => 'Penggabungan Polbis dengan Politeknik Tiara Bunda',
                'd'      => 'Politeknik Bisnis Digital Indonesia (Polbis) resmi menjadi bagian dari Univeritas Tiara Bunda',
                'tgl'    => '12 Nov 2026',
                'img'    => 'info/breaking.webp',
                'urip'   => true,
                'urutan' => 5,
            ],
            // [
            //     'k'      => 'Draf Internal',
            //     't'      => 'Rencana Kerja Sama Dengan Rumah Sakit Mitra',
            //     'd'      => 'Dokumen ini masih dalam tahap draf dan tidak akan muncul di landing page karena status urip = false.',
            //     'tgl'    => '20 Nov 2026',
            //     'img'    => 'info/draf-kerjasama.webp',
            //     'urip'   => false,
            //     'urutan' => 6,
            // ],
        ];

        foreach ($data as $item) {
            Info::create($item);
        }
    }
}
