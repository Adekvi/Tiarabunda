<?php

namespace Database\Seeders\Landing\Layanan;

use App\Models\Layanan\Kemahasiswaan;
use Illuminate\Database\Seeder;

class KemahaSeeder extends Seeder
{
    public function run(): void
    {
        Kemahasiswaan::withTrashed()
            ->whereIn('judhul', ['Beasiswa Prestasi Akademik', 'Beasiswa KIP-Kuliah'])
            ->forceDelete();

        $rows = [
            [
                'judhul' => 'Biro Kemahasiswaan & Alumni',
                'isi' => 'Biro yang mengelola layanan kemahasiswaan, beasiswa internal/eksternal, organisasi mahasiswa, dan hubungan alumni. Menyediakan layanan konsultasi mahasiswa setiap hari kerja.',
                'data_tambahan' => [
                    'ikon' => 'users',
                    'warna' => 'blue',
                    'kontak' => '(021) 555-0201',
                    'email' => 'kemahasiswaan@tiarabunda.ac.id',
                    'lokasi' => 'Gedung Rektorat Lt. 1, Ruang 105',
                    'url_portal' => 'https://tiarabunda.ac.id/kemahasiswaan',
                ],
                'urutan' => 1,
                'urip' => true,
            ],
            [
                'judhul' => 'Unit Kegiatan Mahasiswa (UKM)',
                'isi' => 'Wadah kegiatan olahraga mahasiswa: futsal, basket, voli, badminton, dan atletik. Latihan rutin setiap Selasa dan Kamis.',
                'data_tambahan' => [
                    'ikon' => 'star',
                    'warna' => 'orange',
                    'kontak' => '0812-3456-7890',
                    'email' => 'ukm.olahraga@tiarabunda.ac.id',
                    'lokasi' => 'Gedung Olahraga Kampus A',
                    'url_portal' => 'https://tiarabunda.ac.id/ukm/olahraga',
                ],
                'urutan' => 2,
                'urip' => true,
            ],
            [
                'judhul' => 'Layanan Konseling Mahasiswa',
                'isi' => 'Konsultasi psikologi gratis untuk mahasiswa yang mengalami stres akademik, masalah pribadi, atau kebutuhan pengembangan diri. Dijaga kerahasiaannya.',
                'data_tambahan' => [
                    'ikon' => 'heart',
                    'warna' => 'red',
                    'kontak' => '0813-5566-7788',
                    'email' => 'konseling@tiarabunda.ac.id',
                    'lokasi' => 'Gedung Student Center Lt. 1, Ruang BK',
                    'url_portal' => 'https://tiarabunda.ac.id/konseling',
                ],
                'urutan' => 3,
                'urip' => true,
            ],
            [
                'judhul' => 'Badan Eksekutif Mahasiswa (BEM)',
                'isi' => 'Organisasi mahasiswa tingkat universitas yang menaungi aspirasi mahasiswa dan kegiatan lintas fakultas. Sekretariat terbuka setiap hari kerja.',
                'data_tambahan' => [
                    'ikon' => 'users',
                    'warna' => 'blue',
                    'kontak' => '0812-9988-7766',
                    'email' => 'bem@tiarabunda.ac.id',
                    'lokasi' => 'Gedung Student Center Lt. 3',
                    'url_portal' => 'https://tiarabunda.ac.id/bem',
                ],
                'urutan' => 4,
                'urip' => true,
            ],
            [
                'judhul' => 'Layanan Kesejahteraan Mahasiswa',
                'isi' => 'Bantuan darurat bagi mahasiswa yang mengalami musibah, termasuk bantuan biaya hidup, konseling keluarga, dan pendampingan hukum ringan.',
                'data_tambahan' => [
                    'ikon' => 'heart',
                    'warna' => 'green',
                    'kontak' => '(021) 555-0204',
                    'email' => 'kesejahteraan@tiarabunda.ac.id',
                    'lokasi' => 'Biro Kemahasiswaan, Gedung Rektorat Lt. 1',
                ],
                'urutan' => 5,
                'urip' => true,
            ],
            [
                'judhul' => 'Komunitas Mahasiswa Berprestasi',
                'isi' => 'Komunitas bagi mahasiswa penerima beasiswa dan peraih prestasi nasional/internasional. Menyediakan mentoring dan networking.',
                'data_tambahan' => [
                    'ikon' => 'star',
                    'warna' => 'purple',
                    'email' => 'prestasi@tiarabunda.ac.id',
                    'lokasi' => 'Gedung Student Center Lt. 3',
                ],
                'urutan' => 6,
                'urip' => true,
            ],
        ];

        foreach ($rows as $row) {
            Kemahasiswaan::query()->updateOrCreate(
                ['judhul' => $row['judhul']],
                $row
            );
        }

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
