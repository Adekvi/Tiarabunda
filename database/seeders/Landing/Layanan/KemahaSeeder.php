<?php

namespace Database\Seeders\Landing\Layanan;

use App\Models\Layanan\Kemahasiswaan;
use Illuminate\Database\Seeder;

class KemahaSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'judhul' => 'Biro Kemahasiswaan & Alumni',
                'isi' => 'Biro yang mengelola layanan kemahasiswaan, beasiswa internal/eksternal, organisasi mahasiswa, dan hubungan alumni. Menyediakan layanan konsultasi mahasiswa setiap hari kerja.',
                'data_tambahan' => [
                    'kontak' => '(021) 555-0201',
                    'email' => 'kemahasiswaan@tiarabunda.ac.id',
                    'lokasi' => 'Gedung Rektorat Lt. 1, Ruang 105',
                    'url_portal' => 'https://tiarabunda.ac.id/kemahasiswaan',
                ],
                'urutan' => 1,
                'urip' => true,
            ],
            [
                'judhul' => 'Unit Kegiatan Mahasiswa (UKM) Olahraga',
                'isi' => 'Wadah kegiatan olahraga mahasiswa: futsal, basket, voli, badminton, dan atletik. Latihan rutin setiap Selasa dan Kamis.',
                'data_tambahan' => [
                    'kontak' => '0812-3456-7890',
                    'email' => 'ukm.olahraga@tiarabunda.ac.id',
                    'lokasi' => 'Gedung Olahraga Kampus A',
                    'url_portal' => 'https://tiarabunda.ac.id/ukm/olahraga',
                ],
                'urutan' => 2,
                'urip' => true,
            ],
            [
                'judhul' => 'Unit Kegiatan Mahasiswa (UKM) Seni & Budaya',
                'isi' => 'Mewadahi minat seni mahasiswa: paduan suara, tari tradisional, teater, dan band kampus. Terbuka untuk semua fakultas.',
                'data_tambahan' => [
                    'kontak' => '0813-2233-4455',
                    'email' => 'ukm.seni@tiarabunda.ac.id',
                    'lokasi' => 'Gedung Student Center Lt. 2',
                ],
                'urutan' => 3,
                'urip' => true,
            ],
            [
                'judhul' => 'Beasiswa Prestasi Akademik',
                'isi' => 'Program beasiswa bagi mahasiswa berprestasi akademik dengan IPK minimal 3.50. Mencakup potongan UKT hingga 100% selama 2 semester.',
                'data_tambahan' => [
                    'kontak' => '(021) 555-0202',
                    'email' => 'beasiswa@tiarabunda.ac.id',
                    'lokasi' => 'Biro Kemahasiswaan, Gedung Rektorat Lt. 1',
                    'url_portal' => 'https://tiarabunda.ac.id/beasiswa/prestasi',
                ],
                'urutan' => 4,
                'urip' => true,
            ],
            [
                'judhul' => 'Beasiswa KIP-Kuliah',
                'isi' => 'Bantuan biaya pendidikan dari pemerintah bagi mahasiswa dari keluarga ekonomi kurang mampu. Pendaftaran dibuka setiap awal semester.',
                'data_tambahan' => [
                    'kontak' => '(021) 555-0203',
                    'email' => 'kip@tiarabunda.ac.id',
                    'lokasi' => 'Biro Kemahasiswaan, Gedung Rektorat Lt. 1',
                    'url_portal' => 'https://kip-kuliah.kemdikbud.go.id',
                ],
                'urutan' => 5,
                'urip' => true,
            ],
            [
                'judhul' => 'Layanan Konseling Mahasiswa',
                'isi' => 'Konsultasi psikologi gratis untuk mahasiswa yang mengalami stres akademik, masalah pribadi, atau kebutuhan pengembangan diri. Dijaga kerahasiaannya.',
                'data_tambahan' => [
                    'kontak' => '0813-5566-7788',
                    'email' => 'konseling@tiarabunda.ac.id',
                    'lokasi' => 'Gedung Student Center Lt. 1, Ruang BK',
                    'url_portal' => 'https://tiarabunda.ac.id/konseling',
                ],
                'urutan' => 6,
                'urip' => true,
            ],
            [
                'judhul' => 'Badan Eksekutif Mahasiswa (BEM)',
                'isi' => 'Organisasi mahasiswa tingkat universitas yang menaungi aspirasi mahasiswa dan kegiatan lintas fakultas. Sekretariat terbuka setiap hari kerja.',
                'data_tambahan' => [
                    'kontak' => '0812-9988-7766',
                    'email' => 'bem@tiarabunda.ac.id',
                    'lokasi' => 'Gedung Student Center Lt. 3',
                    'url_portal' => 'https://tiarabunda.ac.id/bem',
                ],
                'urutan' => 7,
                'urip' => true,
            ],
            [
                'judhul' => 'Layanan Kesejahteraan Mahasiswa',
                'isi' => 'Bantuan darurat bagi mahasiswa yang mengalami musibah, termasuk bantuan biaya hidup, konseling keluarga, dan pendampingan hukum ringan.',
                'data_tambahan' => [
                    'kontak' => '(021) 555-0204',
                    'email' => 'kesejahteraan@tiarabunda.ac.id',
                    'lokasi' => 'Biro Kemahasiswaan, Gedung Rektorat Lt. 1',
                ],
                'urutan' => 8,
                'urip' => true,
            ],
            [
                'judhul' => 'Pusat Karier & Alumni',
                'isi' => 'Layanan bimbingan karier, pelatihan CV & interview, job fair tahunan, dan tracer study alumni. Terbuka untuk mahasiswa semester akhir dan alumni.',
                'data_tambahan' => [
                    'kontak' => '(021) 555-0205',
                    'email' => 'karier@tiarabunda.ac.id',
                    'lokasi' => 'Gedung Student Center Lt. 2',
                    'url_portal' => 'https://karier.tiarabunda.ac.id',
                ],
                'urutan' => 9,
                'urip' => true,
            ],
            [
                'judhul' => 'Komunitas Mahasiswa Berprestasi',
                'isi' => 'Komunitas bagi mahasiswa penerima beasiswa dan peraih prestasi nasional/internasional. Menyediakan mentoring dan networking.',
                'data_tambahan' => [
                    'email' => 'prestasi@tiarabunda.ac.id',
                    'lokasi' => 'Gedung Student Center Lt. 3',
                ],
                'urutan' => 10,
                'urip' => true,
            ],
        ];

        foreach ($rows as $row) {
            Kemahasiswaan::query()->updateOrCreate(
                ['judhul' => $row['judhul']],
                $row
            );
        }

        // Contoh nonaktif (uji filter status)
        Kemahasiswaan::query()->updateOrCreate(
            ['judhul' => 'Kegiatan Mahasiswa 2022 (Arsip)'],
            [
                'judhul' => 'Kegiatan Mahasiswa 2022 (Arsip)',
                'isi' => 'Dokumentasi kegiatan mahasiswa tahun 2022. Disimpan sebagai arsip.',
                'data_tambahan' => [
                    'kontak' => '(021) 555-0002',
                    'email' => 'arsip.kemahasiswaan@tiarabunda.ac.id',
                ],
                'urutan' => 99,
                'urip' => false,
            ]
        );

        // Contoh soft-deleted (uji mode sampah)
        $trashed = Kemahasiswaan::query()->firstOrCreate(
            ['judhul' => 'UKM Lama (Dihapus)'],
            [
                'judhul' => 'UKM Lama (Dihapus)',
                'isi' => 'Konten ini sengaja dihapus untuk uji fitur sampah.',
                'data_tambahan' => [
                    'kontak' => '0812-0000-0000',
                    'email' => 'ukm.lama@tiarabunda.ac.id',
                ],
                'urutan' => 98,
                'urip' => false,
            ]
        );
        $trashed->delete();
    }
}
