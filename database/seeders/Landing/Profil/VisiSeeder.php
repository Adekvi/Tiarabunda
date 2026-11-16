<?php

namespace Database\Seeders\Landing\Profil;

use App\Models\Profil\Visimisi;
use App\Models\User;
use Illuminate\Database\Seeder;

class VisiSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::query()->value('id');

        $items = [
            // VISI
            [
                'bagean'  => 'visi',
                'judhul'  => 'Visi',
                'isi'     => 'Menjadi Perguruan Tinggi Vokasi Unggul yang Menghasilkan Lulusan Kompeten dan Inovatif dalam Pemecahan Masalah Kesehatan di Jawa Barat Tahun 2032.',
                'dhaptar' => null,
                'urutan'  => 1,
                'urip'    => true,
                'dianyari_dening' => $adminId,
            ],
            // MISI
            [
                'bagean'  => 'misi',
                'judhul'  => 'Misi',
                'isi'     => null,
                'dhaptar' => [
                    'Menyelenggarakan pendidikan serta pengajaran unggul yang menghasilkan lulusan kompeten dan inovatif dalam pemecahan masalah kesehatan.',
                    'Menyelenggarakan penelitian untuk pengembangan serta pemanfaatan ilmu pengetahuan dan teknologi kesehatan dalam pemecahan masalah kesehatan.',
                    'Menyelenggarakan pengabdian kepada masyarakat melalui pemanfaatan hasil penelitian untuk meningkatkan kualitas kesehatan masyarakat sesuai dengan evidence based.',
                    'Menyelenggarakan tata kelola yang sehat, transparan, berkualitas, dan akuntabel sebagai wujud penyelenggaraan institusi yang sehat.',
                    'Menyelenggarakan kerjasama dengan pemerintah, perguruan tinggi dan industri baik dalam maupun luar negeri untuk mendukung pelaksanaan tri dharma perguruan tinggi.',
                ],
                'urutan'  => 2,
                'urip'    => true,
                'dianyari_dening' => $adminId,
            ],
            [
                'bagean'  => 'nilai',
                'judhul'  => 'Nilai yang Kami Jaga',
                'isi'     => 'Nilai-nilai ini menjadi rujukan dalam setiap keputusan akademik dan layanan mahasiswa.',
                'dhaptar' => [
                    'Kompetensi — keterampilan yang terukur dan siap kerja.',
                    'Integritas — proses belajar dan layanan yang dapat dipertanggungjawabkan.',
                    'Kolaborasi — kemitraan sebagai bagian dari ekosistem belajar.',
                    'Kebermanfaatan — dampak nyata bagi masyarakat dan lulusan.',
                ],
                'urutan'  => 3,
                'urip'    => true,
                'dianyari_dening' => $adminId,
            ],
        ];

        foreach ($items as $item) {
            Visimisi::query()->create($item);
        }
    }
}
