<?php

namespace Database\Seeders\Landing;

use App\Models\Landing\Popup;
use Illuminate\Database\Seeder;

class PopupSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'kategori'      => 'pmb',
                'badge'         => 'Penerimaan Mahasiswa Baru',
                'judul'         => 'Pendaftaran Mahasiswa Baru & Brosur Resmi',
                'deskripsi'     => 'Cek jadwal gelombang pendaftaran, jalur masuk, rincian biaya kuliah, serta unduh brosur resmi Universitas Tiara Bunda.',
                'gambar'        => null,
                'teks_tombol'   => 'Daftar PMB Sekarang',
                'url_tombol'    => 'https://forms.gle/eDmoP7SdmJyS1YxD6',
                'buka_tab_baru' => true,
                'teks_sekunder' => 'Lihat Brosur & Info',
                'url_sekunder'  => '/layanan/akademik',
                'urutan'        => 1,
                'is_active'     => true,
            ],
            [
                'kategori'      => 'jurusan',
                'badge'         => 'Pilihan Program Studi',
                'judul'         => 'Temukan Jurusan yang Cocok untuk Anda',
                'deskripsi'     => 'Jelajahi fakultas, pilihan jenjang D3, D4, S1, dan Profesi, serta prospek karier yang sesuai minat dan bakat Anda.',
                'gambar'        => null,
                'teks_tombol'   => 'Jelajahi Jurusan',
                'url_tombol'    => '/pendidikan/program-pendidikan',
                'buka_tab_baru' => false,
                'teks_sekunder' => null,
                'url_sekunder'  => null,
                'urutan'        => 2,
                'is_active'     => true,
            ],
            [
                'kategori'      => 'beasiswa',
                'badge'         => 'Dukungan Biaya Pendidikan',
                'judul'         => 'Program Beasiswa Kuliah',
                'deskripsi'     => 'Informasi lengkap mengenai KIP Kuliah, beasiswa prestasi akademik maupun non-akademik, dan skema keringanan biaya.',
                'gambar'        => null,
                'teks_tombol'   => 'Lihat Info Beasiswa',
                'url_tombol'    => '/layanan/kemahasiswaan',
                'buka_tab_baru' => false,
                'teks_sekunder' => null,
                'url_sekunder'  => null,
                'urutan'        => 3,
                'is_active'     => true,
            ],
            [
                'kategori'      => 'info',
                'badge'         => 'Informasi & Layanan Kampus',
                'judul'         => 'Pusat Informasi & Panduan Lengkap',
                'deskripsi'     => 'Akses cepat ke jawaban pertanyaan umum (FAQ), kalender kegiatan kampus, layanan akademik, serta kontak resmi admisi.',
                'gambar'        => null,
                'teks_tombol'   => 'Buka Pusat Informasi',
                'url_tombol'    => '/informasi/faq',
                'buka_tab_baru' => false,
                'teks_sekunder' => 'Berita Kampus',
                'url_sekunder'  => '/informasi/berita-terbaru',
                'urutan'        => 4,
                'is_active'     => true,
            ],
        ];

        foreach ($rows as $row) {
            Popup::withTrashed()->updateOrCreate(
                ['kategori' => $row['kategori'], 'judul' => $row['judul']],
                array_merge($row, ['deleted_at' => null])
            );
        }

        Popup::flushCache();
    }
}
