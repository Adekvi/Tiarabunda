<?php

namespace Database\Seeders\Landing\Pendidikan;

use App\Models\Pendidikan\Pmb;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PmbSeeder extends Seeder
{
    public function run(): void
    {
        $today = now()->startOfDay();

        /**
         * Struktur data:
         * - jeneng        : nama jalur
         * - tandha        : slug (unique)
         * - katrangan     : HTML (sesuai form RTE)
         * - syarat        : array string
         * - langkah       : array string
         * - wektu_bukak   : \Carbon\Carbon|string|null
         * - wektu_tutup   : \Carbon\Carbon|string|null
         * - urutan        : int
         * - urip          : bool
         */
        $items = [
            // 1. Sedang dibuka (dibuka 1 bulan lalu, tutup 2 bulan lagi)
            [
                'jeneng'      => 'Jalur Reguler',
                'tandha'      => 'jalur-reguler',
                'katrangan'   => '<p>Jalur pendaftaran utama bagi <strong>calon mahasiswa baru</strong> yang belum memiliki prestasi khusus. Terbuka untuk seluruh program studi.</p><p>Pastikan seluruh dokumen telah diunggah sebelum masa pendaftaran berakhir.</p>',
                'syarat'      => [
                    'Lulusan SMA/SMK/MA sederajat',
                    'Fotokopi ijazah dilegalisir',
                    'Fotokopi rapor semester 1–5',
                    'Pas foto 3×4 latar merah (3 lembar)',
                    'Kartu Keluarga',
                    'Membayar biaya pendaftaran',
                ],
                'langkah'     => [
                    'Membuat akun pendaftaran di portal PMB',
                    'Mengisi formulir data diri',
                    'Mengunggah berkas persyaratan',
                    'Melakukan pembayaran biaya pendaftaran',
                    'Mengikuti tes seleksi',
                    'Melihat pengumuman hasil seleksi',
                    'Melakukan daftar ulang',
                ],
                'wektu_bukak' => $today->copy()->subMonth()->toDateString(),
                'wektu_tutup' => $today->copy()->addMonths(2)->toDateString(),
                'urutan'      => 1,
                'urip'        => true,
            ],

            // 2. Segera tutup (tutup 7 hari lagi)
            [
                'jeneng'      => 'Jalur Prestasi Akademik',
                'tandha'      => 'jalur-prestasi-akademik',
                'katrangan'   => '<p>Jalur khusus bagi calon mahasiswa dengan <strong>prestasi akademik unggul</strong> di bidang sains, olimpiade, atau kompetisi nasional.</p>',
                'syarat'      => [
                    'Siswa aktif kelas XII atau lulusan tahun berjalan',
                    'Nilai rata-rata rapor minimal 85',
                    'Sertifikat prestasi tingkat kabupaten/kota/nasional',
                    'Surat keterangan peringkat kelas',
                ],
                'langkah'     => [
                    'Mendaftar melalui portal PMB',
                    'Mengunggah sertifikat prestasi',
                    'Verifikasi dokumen oleh panitia',
                    'Wawancara singkat',
                    'Pengumuman hasil',
                ],
                'wektu_bukak' => $today->copy()->subWeeks(3)->toDateString(),
                'wektu_tutup' => $today->copy()->addDays(7)->toDateString(),
                'urutan'      => 2,
                'urip'        => true,
            ],

            // 3. Segera tutup (tutup 14 hari lagi)
            [
                'jeneng'      => 'Jalur KIP Kuliah',
                'tandha'      => 'jalur-kip-kuliah',
                'katrangan'   => '<p>Jalur bantuan <strong>Kartu Indonesia Pintar (KIP) Kuliah</strong> untuk calon mahasiswa dari keluarga kurang mampu yang berprestasi.</p><blockquote>Wajib memiliki KIP atau terdaftar di DTKS.</blockquote>',
                'syarat'      => [
                    'Memiliki KIP atau terdaftar DTKS',
                    'Lulusan SMA/SMK/MA sederajat',
                    'Tidak sedang menerima beasiswa lain',
                    'Mengunggah foto rumah & kondisi ekonomi',
                    'Surat keterangan tidak mampu',
                ],
                'langkah'     => [
                    'Membuat akun di portal KIP Kuliah',
                    'Mengisi formulir dan data ekonomi',
                    'Mengunggah berkas pendukung',
                    'Seleksi administrasi',
                    'Verifikasi lapangan (jika diperlukan)',
                    'Pengumuman penerima KIP',
                ],
                'wektu_bukak' => $today->copy()->subWeeks(2)->toDateString(),
                'wektu_tutup' => $today->copy()->addDays(14)->toDateString(),
                'urutan'      => 3,
                'urip'        => true,
            ],

            // 4. Belum dibuka (dibuka 1 bulan lagi)
            [
                'jeneng'      => 'Jalur Mandiri Gelombang 2',
                'tandha'      => 'jalur-mandiri-gelombang-2',
                'katrangan'   => '<p>Gelombang kedua jalur mandiri untuk calon mahasiswa yang belum berkesempatan mendaftar pada gelombang pertama.</p>',
                'syarat'      => [
                    'Lulusan SMA/SMK/MA sederajat',
                    'Fotokopi ijazah atau surat keterangan lulus',
                    'Pas foto 3×4 latar biru',
                    'Membayar biaya pendaftaran gelombang 2',
                ],
                'langkah'     => [
                    'Mendaftar online',
                    'Unggah berkas',
                    'Pembayaran',
                    'Tes seleksi mandiri',
                    'Pengumuman & daftar ulang',
                ],
                'wektu_bukak' => $today->copy()->addMonth()->toDateString(),
                'wektu_tutup' => $today->copy()->addMonths(2)->toDateString(),
                'urutan'      => 4,
                'urip'        => true,
            ],

            // 5. Sudah ditutup (selesai 1 bulan lalu)
            [
                'jeneng'      => 'Jalur Mandiri Gelombang 1',
                'tandha'      => 'jalur-mandiri-gelombang-1',
                'katrangan'   => '<p>Pendaftaran gelombang pertama jalur mandiri telah <strong>ditutup</strong>. Silakan menunggu informasi gelombang berikutnya.</p>',
                'syarat'      => [
                    'Lulusan SMA/SMK/MA sederajat',
                    'Fotokopi ijazah dilegalisir',
                    'Membayar biaya pendaftaran',
                ],
                'langkah'     => [
                    'Mendaftar online',
                    'Unggah berkas',
                    'Pembayaran',
                    'Tes seleksi',
                    'Pengumuman',
                ],
                'wektu_bukak' => $today->copy()->subMonths(3)->toDateString(),
                'wektu_tutup' => $today->copy()->subMonth()->toDateString(),
                'urutan'      => 5,
                'urip'        => true,
            ],

            // 6. Nonaktif (disembunyikan dari publik)
            [
                'jeneng'      => 'Jalur Beasiswa Unggulan',
                'tandha'      => 'jalur-beasiswa-unggulan',
                'katrangan'   => '<p>Jalur beasiswa penuh untuk mahasiswa berprestasi tinggi. <em>Program sedang ditinjau ulang dan untuk sementara dinonaktifkan.</em></p>',
                'syarat'      => [
                    'Peringkat 1–3 di sekolah',
                    'Sertifikat prestasi nasional/internasional',
                    'Rekomendasi kepala sekolah',
                ],
                'langkah'     => [
                    'Pendaftaran online',
                    'Seleksi berkas',
                    'Wawancara panel',
                    'Pengumuman penerima',
                ],
                'wektu_bukak' => null,
                'wektu_tutup' => null,
                'urutan'      => 6,
                'urip'        => false,
            ],

            // 7. Tanpa jadwal (fleksibel)
            [
                'jeneng'      => 'Jalur RPL (Rekognisi Pembelajaran Lampau)',
                'tandha'      => 'jalur-rpl',
                'katrangan'   => '<p>Jalur khusus bagi <strong>praktisi/profesional</strong> yang ingin melanjutkan studi dengan mengakui pengalaman kerja sebagai kredit pembelajaran.</p>',
                'syarat'      => [
                    'Minimal 2 tahun pengalaman kerja relevan',
                    'Portofolio hasil kerja',
                    'Surat keterangan kerja',
                    'Transkrip nilai sebelumnya (jika ada)',
                ],
                'langkah'     => [
                    'Konsultasi awal dengan program studi',
                    'Pengajuan portofolio',
                    'Asesmen oleh tim RPL',
                    'Penetapan kredit',
                    'Registrasi mahasiswa',
                ],
                'wektu_bukak' => null,
                'wektu_tutup' => null,
                'urutan'      => 7,
                'urip'        => true,
            ],
        ];

        foreach ($items as $item) {
            Pmb::withTrashed()
                ->where('tandha', $item['tandha'])
                ->forceDelete();

            Pmb::query()->create([
                'jeneng'      => $item['jeneng'],
                'tandha'      => $item['tandha'] ?: Str::slug($item['jeneng']),
                'katrangan'   => $item['katrangan'],
                'syarat'      => $item['syarat'] ?: [],
                'langkah'     => $item['langkah'] ?: [],
                'wektu_bukak' => $item['wektu_bukak'],
                'wektu_tutup' => $item['wektu_tutup'],
                'urutan'      => (int) ($item['urutan'] ?? 0),
                'urip'        => (bool) ($item['urip'] ?? true),
            ]);
        }

        $this->command?->info('✔ ' . count($items) . ' jalur PMB berhasil di-seed.');
        $this->command?->newLine();

        $this->command?->table(
            ['#', 'Jalur', 'Slug', 'Buka', 'Tutup', 'Aktif'],
            collect($items)->values()->map(fn($it, $i) => [
                $i + 1,
                $it['jeneng'],
                $it['tandha'],
                $it['wektu_bukak'] ?? '—',
                $it['wektu_tutup'] ?? '—',
                $it['urip'] ? 'Ya' : 'Tidak',
            ])->all()
        );
    }
}
