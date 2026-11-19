<?php

namespace Database\Seeders\Landing\Lembaga;

use App\Models\Lembaga\Lppm;
use Illuminate\Database\Seeder;

class LppmSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'judhul'   => 'Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)',
                'tandha'   => 'profil-lppm',
                'kategori' => 'profil',
                'isi'      => '<p>LPPM Universitas Tiara Bunda merupakan unsur pelaksana akademik yang mengoordinasikan, memfasilitasi, dan mengevaluasi kegiatan penelitian terapan serta pengabdian kepada masyarakat lintas fakultas untuk menghasilkan inovasi yang berdampak bagi industri dan masyarakat.</p>',
                'data_tambahan' => [
                    'ikon'        => 'award',
                    'warna'       => 'blue',
                    'lokasi'      => 'Gedung Rektorat Lt. 2, Ruang LPPM',
                    'jam_layanan' => 'Senin–Jumat 08.00–16.00 WIB',
                    'kontak'      => '(021) 555-0301',
                    'email'       => 'lppm@tiarabunda.ac.id',
                    'url_portal'  => 'https://lppm.poltektiarabundadepok.ac.id/',
                ],
                'urutan' => 1,
                'urip'   => true,
            ],
            [
                'judhul'   => 'Skema Penelitian Dosen Pemula & Terapan',
                'tandha'   => 'penelitian-dosen-pemula-terapan',
                'kategori' => 'penelitian',
                'isi'      => '<p>Pendanaan hibah penelitian internal maupun fasilitasi hibah Kemendikbudristek (BIMA) bagi dosen tetap untuk menghasilkan purwarupa, model kebijakan, serta publikasi ilmiah bereputasi.</p><ul><li>Penelitian Dosen Pemula (PDP)</li><li>Penelitian Terapan Unggulan Perguruan Tinggi</li><li>Penelitian Kolaborasi Dosen & Mahasiswa</li></ul>',
                'data_tambahan' => [
                    'ikon'       => 'microscope',
                    'warna'      => 'blue',
                    'jadwal'     => 'Pembukaan Proposal: Februari & Agustus',
                    'kontak'     => '(021) 555-0302',
                    'email'      => 'penelitian.lppm@tiarabunda.ac.id',
                    'url_portal' => 'https://bima.kemdikbud.go.id/',
                ],
                'urutan' => 2,
                'urip'   => true,
            ],
            [
                'judhul'   => 'Program Kemitraan Masyarakat (PkM) & Desa Binaan',
                'tandha'   => 'program-kemitraan-masyarakat',
                'kategori' => 'pengabdian',
                'isi'      => '<p>Program hilirisasi hasil riset kampus kepada UMKM, sekolah mitra, fasilitas kesehatan, dan kelurahan binaan melalui pelatihan, pendampingan teknologi tepat guna, serta pemberdayaan ekonomi lokal.</p><ul><li>Pendampingan Digitalisasi UMKM & Logistik</li><li>Edukasi Kesehatan Ibu, Anak & Farmasi Komunitas</li><li>Pelatihan Inovasi Pembelajaran bagi Guru Mitra</li></ul>',
                'data_tambahan' => [
                    'ikon'   => 'users',
                    'warna'  => 'green',
                    'jadwal' => 'Pelaksanaan: Semester Ganjil & Genap',
                    'kontak' => '(021) 555-0303',
                    'email'  => 'pkm.lppm@tiarabunda.ac.id',
                ],
                'urutan' => 3,
                'urip'   => true,
            ],
            [
                'judhul'   => 'Kuliah Kerja Nyata (KKN) Tematik & Terpadu',
                'tandha'   => 'kkn-tematik-terpadu',
                'kategori' => 'pengabdian',
                'isi'      => '<p>Program pengabdian mahasiswa lintas program studi yang terintegrasi dengan agenda riset dosen dan kebutuhan pembangunan wilayah di Kota Depok dan sekitarnya.</p>',
                'data_tambahan' => [
                    'ikon'   => 'globe',
                    'warna'  => 'orange',
                    'jadwal' => 'Pendaftaran: Mei–Juni setiap tahun',
                    'lokasi' => 'Sekretariat LPPM, Gedung Rektorat Lt. 2',
                    'email'  => 'kkn.lppm@tiarabunda.ac.id',
                ],
                'urutan' => 4,
                'urip'   => true,
            ],
            [
                'judhul'   => 'Rumah Jurnal & Publikasi Ilmiah Kampus',
                'tandha'   => 'rumah-jurnal-publikasi-ilmiah',
                'kategori' => 'publikasi',
                'isi'      => '<p>Pengelolaan jurnal ilmiah berkala terakreditasi SINTA serta pendampingan penulisan artikel ilmiah dan insentif publikasi jurnal nasional maupun internasional bereputasi bagi dosen dan mahasiswa.</p>',
                'data_tambahan' => [
                    'ikon'       => 'book-open',
                    'warna'      => 'purple',
                    'email'      => 'jurnal.lppm@tiarabunda.ac.id',
                    'url_portal' => 'https://jurnal.poltektiarabundadepok.ac.id/',
                ],
                'urutan' => 5,
                'urip'   => true,
            ],
            [
                'judhul'   => 'Sentra Hak Kekayaan Intelektual (HKI) & Paten',
                'tandha'   => 'sentra-hki-dan-inovasi',
                'kategori' => 'hki',
                'isi'      => '<p>Layanan konsultasi, penelusuran kebaruan, dan fasilitasi pendaftaran Hak Cipta, Paten Sederhana, Merek, serta Desain Industri atas karya inovatif dosen dan mahasiswa ke DJKI Kemenkumham.</p>',
                'data_tambahan' => [
                    'ikon'   => 'shield',
                    'warna'  => 'red',
                    'kontak' => '(021) 555-0304',
                    'email'  => 'hki.lppm@tiarabunda.ac.id',
                ],
                'urutan' => 6,
                'urip'   => true,
            ],
        ];

        foreach ($rows as $row) {
            Lppm::query()->updateOrCreate(
                ['tandha' => $row['tandha']],
                $row + ['deleted_at' => null]
            );
        }
    }
}
