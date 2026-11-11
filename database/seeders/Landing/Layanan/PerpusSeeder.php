<?php

namespace Database\Seeders\Landing\Layanan;

use App\Models\Layanan\Perpus;
use Illuminate\Database\Seeder;

class PerpusSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'judhul' => 'Perpustakaan Pusat Universitas Tiara Bunda',
                'isi' => 'Perpustakaan pusat menyediakan lebih dari 25.000 judul buku, jurnal nasional dan internasional, serta akses e-library. Dilengkapi ruang baca ber-AC, ruang diskusi, dan area multimedia.',
                'data_tambahan' => [
                    'url_portal' => 'https://perpus.tiarabunda.ac.id',
                    'jam_layanan' => 'Senin–Jumat 08.00–16.00 WIB',
                    'kontak' => '(021) 555-0101',
                    'email' => 'perpus@tiarabunda.ac.id',
                ],
                'urutan' => 1,
                'urip' => true,
            ],
            [
                'judhul' => 'Perpustakaan Fakultas Teknik',
                'isi' => 'Koleksi khusus bidang teknik sipil, informatika, dan elektro. Menyediakan akses jurnal IEEE, Springer, dan skripsi alumni.',
                'data_tambahan' => [
                    'url_portal' => 'https://perpus.tiarabunda.ac.id/teknik',
                    'jam_layanan' => 'Senin–Kamis 08.00–15.30, Jumat 08.00–11.00',
                    'kontak' => '(021) 555-0110',
                    'email' => 'perpus.teknik@tiarabunda.ac.id',
                ],
                'urutan' => 2,
                'urip' => true,
            ],
            [
                'judhul' => 'Perpustakaan Fakultas Ekonomi & Bisnis',
                'isi' => 'Fokus pada literatur manajemen, akuntansi, dan bisnis digital. Tersedia akses e-book dan database statistik ekonomi.',
                'data_tambahan' => [
                    'url_portal' => 'https://perpus.tiarabunda.ac.id/feb',
                    'jam_layanan' => 'Senin–Jumat 08.30–16.00 WIB',
                    'kontak' => '(021) 555-0120',
                    'email' => 'perpus.feb@tiarabunda.ac.id',
                ],
                'urutan' => 3,
                'urip' => true,
            ],
            [
                'judhul' => 'Ruang Baca Fakultas Keguruan & Ilmu Pendidikan',
                'isi' => 'Ruang baca khusus mahasiswa FKIP dengan koleksi buku pedagogi, psikologi pendidikan, dan kurikulum terbaru.',
                'data_tambahan' => [
                    'jam_layanan' => 'Senin–Jumat 08.00–15.00 WIB',
                    'kontak' => '(021) 555-0130',
                    'email' => 'perpus.fkip@tiarabunda.ac.id',
                ],
                'urutan' => 4,
                'urip' => true,
            ],
            [
                'judhul' => 'Perpustakaan Digital (E-Library)',
                'isi' => 'Portal akses 24 jam untuk e-book, jurnal, dan repositori skripsi/tesis seluruh fakultas. Login menggunakan akun SSO kampus.',
                'data_tambahan' => [
                    'url_portal' => 'https://elibrary.tiarabunda.ac.id',
                    'jam_layanan' => 'Akses 24 jam',
                    'kontak' => '(021) 555-0100',
                    'email' => 'elibrary@tiarabunda.ac.id',
                ],
                'urutan' => 5,
                'urip' => true,
            ],
            [
                'judhul' => 'Perpustakaan Fakultas Ilmu Kesehatan',
                'isi' => 'Koleksi keperawatan, kebidanan, dan kesehatan masyarakat. Menyediakan akses jurnal ProQuest dan PubMed.',
                'data_tambahan' => [
                    'url_portal' => 'https://perpus.tiarabunda.ac.id/fikes',
                    'jam_layanan' => 'Senin–Jumat 08.00–15.30 WIB',
                    'kontak' => '(021) 555-0140',
                    'email' => 'perpus.fikes@tiarabunda.ac.id',
                ],
                'urutan' => 6,
                'urip' => true,
            ],
            [
                'judhul' => 'Perpustakaan Pascasarjana',
                'isi' => 'Koleksi khusus mahasiswa magister: tesis, jurnal internasional, dan buku metodologi penelitian.',
                'data_tambahan' => [
                    'jam_layanan' => 'Senin–Jumat 09.00–16.00 WIB',
                    'kontak' => '(021) 555-0150',
                    'email' => 'perpus.pasca@tiarabunda.ac.id',
                ],
                'urutan' => 7,
                'urip' => true,
            ],
            [
                'judhul' => 'Perpustakaan Fakultas Hukum',
                'isi' => 'Koleksi undang-undang, putusan pengadilan, dan jurnal hukum. Bekerja sama dengan perpustakaan Mahkamah Agung.',
                'data_tambahan' => [
                    'url_portal' => 'https://perpus.tiarabunda.ac.id/hukum',
                    'jam_layanan' => 'Senin–Jumat 08.00–16.00 WIB',
                    'kontak' => '(021) 555-0160',
                    'email' => 'perpus.hukum@tiarabunda.ac.id',
                ],
                'urutan' => 8,
                'urip' => true,
            ],
            [
                'judhul' => 'Ruang Diskusi & Multimedia',
                'isi' => 'Fasilitas reservasi ruang diskusi ber-AC dengan proyektor dan akses internet. Dapat dipesan online untuk kelompok 4–10 orang.',
                'data_tambahan' => [
                    'jam_layanan' => 'Senin–Jumat 08.00–16.00 WIB',
                    'kontak' => '(021) 555-0102',
                    'email' => 'perpus@tiarabunda.ac.id',
                ],
                'urutan' => 9,
                'urip' => true,
            ],
            [
                'judhul' => 'Layanan Repositori Skripsi & Tesis',
                'isi' => 'Akses daring karya ilmiah mahasiswa dan dosen. Mendukung pencarian berdasarkan judul, penulis, dan tahun.',
                'data_tambahan' => [
                    'url_portal' => 'https://repository.tiarabunda.ac.id',
                    'jam_layanan' => 'Akses 24 jam',
                    'kontak' => '(021) 555-0103',
                    'email' => 'repository@tiarabunda.ac.id',
                ],
                'urutan' => 10,
                'urip' => true,
            ],
        ];

        foreach ($rows as $row) {
            Perpus::query()->updateOrCreate(
                ['judhul' => $row['judhul']],
                $row + ['deleted_at' => null]
            );
        }

        // Tambahan contoh nonaktif (untuk uji filter status)
        Perpus::query()->updateOrCreate(
            ['judhul' => 'Perpustakaan Lama (Arsip)'],
            [
                'judhul' => 'Perpustakaan Lama (Arsip)',
                'isi' => 'Perpustakaan lama yang sudah tidak digunakan. Disimpan sebagai arsip konten.',
                'data_tambahan' => [
                    'kontak' => '(021) 555-0001',
                    'email' => 'arsip@tiarabunda.ac.id',
                ],
                'urutan' => 99,
                'urip' => false,
            ]
        );

        // Contoh soft-deleted (untuk uji mode sampah)
        $trashed = Perpus::query()->firstOrCreate(
            ['judhul' => 'Perpustakaan Cabang Lama (Dihapus)'],
            [
                'judhul' => 'Perpustakaan Cabang Lama (Dihapus)',
                'isi' => 'Konten ini sengaja dihapus untuk uji fitur sampah.',
                'data_tambahan' => [
                    'kontak' => '(021) 555-0999',
                    'email' => 'cabang@tiarabunda.ac.id',
                ],
                'urutan' => 98,
                'urip' => false,
            ]
        );
        $trashed->delete();
    }
}
