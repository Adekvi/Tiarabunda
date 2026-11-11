<?php

namespace Database\Seeders\Landing;

use Illuminate\Database\Seeder;
use App\Models\Landing\Hero;
use Illuminate\Support\Facades\File;

class HeroSeeder extends Seeder
{
    public function run(): void
    {
        $targetDir = storage_path('app/public/hero');
        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true, true);
        }

        // Buat gambar dummy (biarkan kosong/none agar animasi lengkungan muncul)
        // Karena kita set 'tipe_media' => 'none', file gambar tidak akan dipakai.

        Hero::updateOrCreate(
            ['id' => 1],
            [
                // Teks Ajakan (Diambil dari esensi brosur)
                'judul' => 'Kuliah Asyik, Banyak Praktik, Lulusan Siap Kerja!',
                'sub_judul' => 'Wujudkan masa depan gemilangmu di Universitas Tiara Bunda. Biaya terjangkau, fasilitas lengkap, dan pendampingan karier hingga kamu sukses.',

                // Tombol 1 (Aksi Utama)
                'teks_tombol_1' => 'Daftar Sekarang',
                'link_tombol_1' => 'https://linktr.ee/PENDAFTARANPTB2025',

                // Tombol 2 (Aksi Sekunder)
                'teks_tombol_2' => 'Lihat Program Studi',
                'link_tombol_2' => '#prodi',

                // Media Setting
                'tipe_media' => 'none', // Ubah ke 'none' agar desain lengkungan asli muncul
                'path_media' => null,
                'path_poster' => null,

                'urip' => true,
            ]
        );
    }
}
