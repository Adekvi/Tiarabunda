<?php

namespace Database\Seeders\Landing\Layanan;

use App\Models\Layanan\Perpus;
use App\Models\Pendidikan\Program\Fakultas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PerpusSeeder extends Seeder
{
    private const PORTAL_PUSAT_URL = 'https://perpus.poltektiarabundadepok.ac.id/';

    public function run(): void
    {
        // Bersihkan data lama & item informasi dasar yang kini ditangani langsung di Blade
        $obsoleteTitles = [
            'Perpustakaan Fakultas Teknik',
            'Perpustakaan Fakultas Ekonomi & Bisnis',
            'Ruang Baca Fakultas Keguruan & Ilmu Pendidikan',
            'Perpustakaan Fakultas Ilmu Kesehatan',
            'Perpustakaan Pascasarjana',
            'Perpustakaan Fakultas Hukum',
            'Perpustakaan Digital (E-Library)',
            'Perpustakaan Digital (E-Library & Repositori)',
            'Layanan Repositori Skripsi & Tesis',
            'Koleksi & Layanan Fakultas Ilmu Kesehatan',
            'Koleksi & Layanan Fakultas Keguruan dan Ilmu Pendidikan',
            'Koleksi & Layanan Fakultas Ilmu Komputer',
            'Koleksi & Layanan Fakultas Ekonomi Bisnis',
            'Ruang Diskusi & Multimedia',
            'Ruang Baca & Diskusi Kelompok',
            'Ketentuan Peminjaman & Keanggotaan',
            'Aturan Peminjaman',
            'Jam Operasional',
            'Layanan & Fasilitas',
        ];

        Perpus::withTrashed()
            ->whereIn('judhul', $obsoleteTitles)
            ->forceDelete();

        $fakultasMeta = [
            'ilmu-kesehatan' => [
                'fokus'       => 'literatur kefarmasian, asuhan kebidanan, keperawatan anestesiologi, serta akses jurnal kesehatan nasional dan internasional.',
                'jam_layanan' => 'Senin–Jumat 08.00–16.00 WIB',
                'kontak'      => '(021) 555-0110',
                'email'       => 'perpus.fik@tiarabunda.ac.id',
                'ikon'        => 'book-open',
                'warna'       => 'blue',
            ],
            'keguruan-dan-ilmu-pendidikan' => [
                'fokus'       => 'koleksi buku pedagogi, psikologi perkembangan anak, kurikulum pendidikan dasar, serta media pembelajaran inovatif.',
                'jam_layanan' => 'Senin–Jumat 08.00–15.30 WIB',
                'kontak'      => '(021) 555-0120',
                'email'       => 'perpus.fkip@tiarabunda.ac.id',
                'ikon'        => 'book-open',
                'warna'       => 'green',
            ],
            'ilmu-komputer' => [
                'fokus'       => 'referensi rekayasa perangkat lunak, sistem informasi, kecerdasan buatan, komputasi awan, serta repositori proyek aplikasi.',
                'jam_layanan' => 'Senin–Jumat 08.00–16.00 WIB',
                'kontak'      => '(021) 555-0130',
                'email'       => 'perpus.fikom@tiarabunda.ac.id',
                'ikon'        => 'layers',
                'warna'       => 'orange',
            ],
            'ekonomi-bisnis' => [
                'fokus'       => 'literatur manajemen rantai pasok (logistik), bisnis digital, e-commerce, kewirausahaan, serta statistik bisnis dan ekonomi.',
                'jam_layanan' => 'Senin–Jumat 08.00–16.00 WIB',
                'kontak'      => '(021) 555-0140',
                'email'       => 'perpus.feb@tiarabunda.ac.id',
                'ikon'        => 'book-open',
                'warna'       => 'blue',
            ],
        ];

        $rows = [
            [
                'judhul' => 'Perpustakaan Pusat Universitas Tiara Bunda',
                'isi' => '<p>Perpustakaan pusat menyediakan ribuan koleksi buku referensi, jurnal ilmiah nasional dan internasional, karya tugas akhir, serta akses katalog digital (OPAC) untuk seluruh sivitas akademika.</p>',
                'data_tambahan' => [
                    'url_portal'  => self::PORTAL_PUSAT_URL,
                    'jam_layanan' => 'Senin–Jumat 08.00–16.00 WIB',
                    'kontak'      => '(021) 555-0101',
                    'email'       => 'perpus@tiarabunda.ac.id',
                    'ikon'        => 'book-open',
                    'warna'       => 'blue',
                ],
                'urutan' => 1,
                'urip'   => true,
            ],
        ];

        // Sinkron otomatis dengan 4 Fakultas & Program Studi dari database
        $fakultasList = Fakultas::query()
            ->where('urip', true)
            ->orderBy('urutan')
            ->with(['prodis' => fn($q) => $q->where('urip', true)->orderBy('urutan')])
            ->get();

        $urutan = 2;
        foreach ($fakultasList as $fak) {
            $slug = $fak->tandha ?: Str::slug($fak->jeneng);
            $meta = $fakultasMeta[$slug] ?? [
                'fokus'       => 'koleksi buku ajar, jurnal ilmiah, dan referensi tugas akhir mahasiswa.',
                'jam_layanan' => 'Senin–Jumat 08.00–16.00 WIB',
                'kontak'      => '(021) 555-0101',
                'email'       => 'perpus@tiarabunda.ac.id',
                'ikon'        => 'book-open',
                'warna'       => 'blue',
            ];

            $namaFakultas = Str::startsWith(Str::lower(trim($fak->jeneng)), 'fakultas ')
                ? trim($fak->jeneng)
                : 'Fakultas ' . trim($fak->jeneng);

            $daftarProdi = $fak->prodis->map(function ($p) {
                return trim(($p->undhak ? $p->undhak . ' ' : '') . $p->jeneng);
            })->filter()->values();

            $prodiSentence = $daftarProdi->isNotEmpty()
                ? ' Mendukung program studi: <strong>' . e($daftarProdi->implode(', ')) . '</strong>.'
                : '';

            $rows[] = [
                'judhul' => 'Perpustakaan ' . $namaFakultas,
                'isi'    => '<p>Menyediakan ' . e($meta['fokus']) . $prodiSentence . '</p>',
                'data_tambahan' => [
                    'url_portal'    => '/layanan/perpustakaan/' . $slug,
                    'status_portal' => 'pengembangan',
                    'fakultas_slug' => $slug,
                    'fakultas_nama' => $namaFakultas,
                    'daftar_prodi'  => $daftarProdi->all(),
                    'jam_layanan'   => $meta['jam_layanan'],
                    'kontak'        => $meta['kontak'],
                    'email'         => $meta['email'],
                    'ikon'          => $meta['ikon'],
                    'warna'         => $meta['warna'],
                ],
                'urutan' => $urutan++,
                'urip'   => true,
            ];
        }

        foreach ($rows as $row) {
            Perpus::query()->updateOrCreate(
                ['judhul' => $row['judhul']],
                $row + ['deleted_at' => null]
            );
        }

        // Contoh nonaktif (untuk uji filter status di Superadmin)
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

        // Contoh soft-deleted (untuk uji mode sampah di Superadmin)
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
