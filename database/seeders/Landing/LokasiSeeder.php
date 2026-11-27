<?php

namespace Database\Seeders\Landing;

use App\Models\Landing\Lokasi;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'kode'      => 'A',
                'badge'     => 'Kampus A • Krukut, Limo',
                'nama'      => 'Kampus A — Krukut Depok',
                'deskripsi' => 'Gedung perkuliahan dan layanan kampus Universitas Tiara Bunda di kawasan Krukut, Limo, Kota Depok.',
                'alamat'    => 'JQXR+58G, Jl. Raya Krukut, RW.2, Krukut, Kec. Limo, Kota Depok, Jawa Barat 16512',
                'jam'       => 'Senin - Jumat, 08.00 - 16.00 WIB (Sabtu - Minggu libur)',
                'telepon'   => '0811-1701-4000',
                'koordinat' => '6°21\'07.4"S 106°47\'26.9"E',
                'akses'     => 'Jl. Raya Krukut, RW.2, Limo, Kota Depok',
                'unggulan'  => ['Gedung Kampus A', 'Layanan Akademik', 'Ruang Perkuliahan', 'Area Parkir'],
                'foto'      => 'landing/assets/img/lokasi/gedungA.webp',
                'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.324218032131!2d106.7882639737625!3d-6.3520552621413655!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ef004057dea3%3A0x1843c6eab3b2edc8!2sPoliteknik%20Tiara%20bunda!5e0!3m2!1sid!2sid!4v1795664734379!5m2!1sid!2sid',
                'map_url'   => 'https://maps.app.goo.gl/bDxSS33WioFayEC9A',
                'urutan'    => 1,
                'urip'      => true,
            ],
            [
                'kode'      => 'B',
                'badge'     => 'Kampus B • Cinere, Depok',
                'nama'      => 'Kampus B — Cinere Depok',
                'deskripsi' => 'Gedung kampus Universitas Tiara Bunda di Jalan Cinere Raya No.17, Kecamatan Cinere, Kota Depok.',
                'alamat'    => 'Jl. Cinere Raya No.17, Cinere, Kec. Cinere, Kota Depok, Jawa Barat 16514',
                'jam'       => 'Senin - Jumat, 08.00 - 16.00 WIB (Sabtu - Minggu libur)',
                'telepon'   => '0217541172',
                'koordinat' => '6°20\'10.9"S 106°46\'53.7"E',
                'akses'     => 'Jl. Cinere Raya No.17, Cinere, Kota Depok',
                'unggulan'  => ['Gedung Kampus B', 'Layanan Informasi', 'Ruang Perkuliahan', 'Akses Strategis'],
                'foto'      => 'landing/assets/img/lokasi/gedungB.webp',
                'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.4449110949886!2d106.77901207376169!3d-6.336370261994494!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ee574d3c1023%3A0x152db9f142806c99!2sPoliteknik%20Tiara%20Bunda!5e0!3m2!1sid!2sid!4v1795664784418!5m2!1sid!2sid',
                'map_url'   => 'https://maps.app.goo.gl/w6dGFHX2gKxfVCXF7',
                'urutan'    => 2,
                'urip'      => true,
            ],
        ];

        foreach ($items as $item) {
            Lokasi::updateOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
