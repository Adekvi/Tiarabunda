<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Pendidikan\PmbService;

class PmbPageContent implements PageContentInterface
{
    public function __construct(private PmbService $pmbService) {}

    public function getData(?string $slug = null): array
    {
        $jalur = $this->pmbService->forPage();

        // Fallback demo bila DB masih kosong
        if ($jalur->isEmpty()) {
            $jalur = $this->demoPmb();
        }

        // Kalau ada slug → cari detail
        $selectedJalur = null;
        if ($slug) {
            $selectedJalur = $jalur->firstWhere('tandha', $slug);

            // Coba cari juga di DB jika belum ketemu (untuk safety)
            if (! $selectedJalur) {
                $selectedJalur = $this->pmbService->findBySlug($slug);
            }

            abort_if($selectedJalur === null, 404, 'Jalur PMB tidak ditemukan.');
        }

        return [
            'pmbJalur'      => $jalur,
            'selectedJalur' => $selectedJalur,
        ];
    }

    private function demoPmb()
    {
        return collect([
            (object) [
                'jeneng'      => 'PMB Gelombang 1',
                'tandha'      => 'pmb-gelombang-1',
                'katrangan'   => 'Pendaftaran awal dengan potongan biaya pendaftaran dan prioritas pilihan kelas.',
                'syarat'      => [
                    'Ijazah/SKL SMA/SMK/MA',
                    'Pas foto 3x4 latar merah',
                    'Fotokopi KTP & Kartu Keluarga',
                    'Nilai rapor semester 1-5',
                ],
                'langkah'     => [
                    'Buat akun di portal PMB',
                    'Lengkapi data diri & unggah dokumen',
                    'Pilih program studi',
                    'Lakukan pembayaran biaya pendaftaran',
                    'Cetak kartu peserta seleksi',
                    'Ikuti seleksi sesuai jadwal',
                    'Pengumuman & daftar ulang',
                ],
                'wektu_bukak' => now()->subDays(10)->toDateString(),
                'wektu_tutup' => now()->addDays(20)->toDateString(),
                'status'      => 'buka',
                'status_label' => 'Dibuka',
                'urutan'      => 1,
            ],
            (object) [
                'jeneng'      => 'PMB Gelombang 2',
                'tandha'      => 'pmb-gelombang-2',
                'katrangan'   => 'Pendaftaran reguler untuk kelas pagi dan sore dengan kuota terbatas.',
                'syarat'      => [
                    'Ijazah/SKL SMA/SMK/MA',
                    'Pas foto 3x4 latar merah',
                    'Fotokopi KTP & Kartu Keluarga',
                ],
                'langkah'     => [
                    'Buat akun di portal PMB',
                    'Lengkapi data diri & unggah dokumen',
                    'Pilih program studi',
                    'Lakukan pembayaran biaya pendaftaran',
                    'Ikuti seleksi tes/CBT',
                    'Pengumuman & daftar ulang',
                ],
                'wektu_bukak' => now()->addDays(30)->toDateString(),
                'wektu_tutup' => now()->addDays(60)->toDateString(),
                'status'      => 'belum',
                'status_label' => 'Segera Dibuka',
                'urutan'      => 2,
            ],
            (object) [
                'jeneng'      => 'Jalur Prestasi',
                'tandha'      => 'jalur-prestasi',
                'katrangan'   => 'Seleksi berdasarkan nilai rapor dan prestasi akademik maupun non-akademik.',
                'syarat'      => [
                    'Ijazah/SKL SMA/SMK/MA',
                    'Sertifikat prestasi (jika ada)',
                    'Pas foto 3x4 latar merah',
                ],
                'langkah'     => [
                    'Buat akun di portal PMB',
                    'Lengkapi data & unggah sertifikat prestasi',
                    'Verifikasi dokumen oleh panitia',
                    'Pengumuman & daftar ulang',
                ],
                'wektu_bukak' => now()->subDays(5)->toDateString(),
                'wektu_tutup' => now()->addDays(15)->toDateString(),
                'status'      => 'buka',
                'status_label' => 'Dibuka',
                'urutan'      => 3,
            ],
            (object) [
                'jeneng'      => 'Jalur Beasiswa KIP Kuliah',
                'tandha'      => 'jalur-kip-kuliah',
                'katrangan'   => 'Bagi pemegang KIP Kuliah dari pemerintah, bebas biaya kuliah sesuai ketentuan.',
                'syarat'      => [
                    'Memiliki KIP Kuliah aktif',
                    'Ijazah/SKL SMA/SMK/MA',
                    'Fotokopi KTP & KK',
                    'Surat keterangan tidak mampu',
                ],
                'langkah'     => [
                    'Registrasi KIP Kuliah di portal resmi',
                    'Buat akun PMB kampus',
                    'Lengkapi data & unggah KIP',
                    'Verifikasi & seleksi',
                    'Pengumuman & daftar ulang',
                ],
                'wektu_bukak' => now()->subDays(2)->toDateString(),
                'wektu_tutup' => now()->addDays(40)->toDateString(),
                'status'      => 'buka',
                'status_label' => 'Dibuka',
                'urutan'      => 4,
            ],
        ]);
    }
}
