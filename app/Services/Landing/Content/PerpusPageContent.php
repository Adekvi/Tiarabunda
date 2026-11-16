<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Layanan\PerpusService;

class PerpusPageContent implements PageContentInterface
{
    public function __construct(private PerpusService $perpusService) {}

    public function getData(?string $slug = null): array
    {
        $perpus = $this->perpusService->forPage();

        // Fallback demo jika kosong
        if ($perpus->isEmpty()) {
            $perpus = $this->demoPerpus();
        }

        // Ambil 1 item pertama untuk banner/highlight (opsional)
        $highlight = $perpus->first();
        $items     = $perpus->slice(1)->values();

        return [
            'perpusHighlight' => $highlight,
            'perpusItems'     => $items,
        ];
    }

    private function demoPerpus()
    {
        return collect([
            (object) [
                'judhul' => 'Selamat Datang di Perpustakaan',
                'isi'    => '<p>Perpustakaan Universitas Tiara Bunda menyediakan berbagai koleksi buku, jurnal, dan ruang baca yang nyaman untuk mendukung kegiatan belajar mahasiswa.</p>',
                'data_tambahan' => ['ikon' => 'book-open', 'warna' => 'blue'],
            ],
            (object) [
                'judhul' => 'Jam Operasional',
                'isi'    => '<ul><li>Senin - Jumat: 08.00 - 20.00 WIB</li><li>Sabtu: 08.00 - 12.00 WIB</li><li>Minggu & Libur Nasional: Tutup</li></ul>',
                'data_tambahan' => ['ikon' => 'clock', 'warna' => 'green'],
            ],
            (object) [
                'judhul' => 'Layanan & Fasilitas',
                'isi'    => '<ul><li>Ruang baca ber-AC</li><li>Akses Wi-Fi gratis</li><li>Peminjaman buku mandiri</li><li>Ruang diskusi kelompok</li><li>Akses jurnal elektronik</li></ul>',
                'data_tambahan' => ['ikon' => 'layers', 'warna' => 'orange'],
            ],
            (object) [
                'judhul' => 'Aturan Peminjaman',
                'isi'    => '<p>Mahasiswa dapat meminjam maksimal 3 buku untuk jangka waktu 7 hari. Keterlambatan pengembalian akan dikenakan denda sebesar Rp 1.000/hari/buku.</p>',
                'data_tambahan' => ['ikon' => 'shield-alert', 'warna' => 'red'],
            ],
        ]);
    }
}
