<?php

namespace Database\Seeders\Landing\Profil;

use App\Models\Profil\Sejarah;
use App\Models\User;
use Illuminate\Database\Seeder;

class SejarahSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::query()->value('id');

        $items = [
            [
                'judhul' => 'Dari Kebutuhan Lapangan, Lahir Institusi Vokasi',
                'isi' => <<<HTML
<p>Politeknik Tiara Bunda lahir dari sebuah pemikiran sederhana: kebutuhan dunia industri dan layanan kesehatan akan tenaga profesional yang tidak hanya memahami teori, tetapi juga terampil dalam praktik.</p>

<p>Berawal dari program pelatihan vokasi berskala kecil, institusi ini terus bertumbuh dengan membuka program-program studi yang relevan dengan kebutuhan zaman. Fokus pada praktik, magang industri, dan pendampingan karier menjadi ciri khas yang dipegang hingga hari ini.</p>

<p>Hari ini, Universitas Tiara Bunda hadir sebagai wadah bagi generasi muda untuk tumbuh, belajar, dan mempersiapkan diri menjadi profesional di bidangnya.</p>
HTML,
                'kronologi' => [
                    [
                        'tahun' => '2015',
                        'judhul' => 'Pendirian Institusi',
                        'isi' => 'Langkah awal dimulai dengan membuka program pelatihan vokasi skala kecil.',
                    ],
                    [
                        'tahun' => '2018',
                        'judhul' => 'Ekspansi Program Studi',
                        'isi' => 'Membuka program studi kebidanan dan farmasi yang disesuaikan dengan kebutuhan industri.',
                    ],
                    [
                        'tahun' => '2022',
                        'judhul' => 'Akreditasi Institusi',
                        'isi' => 'Meraih akreditasi B dari BAN-PT sebagai pengakuan mutu pendidikan.',
                    ],
                    [
                        'tahun' => 'Hari ini',
                        'judhul' => 'Menuju Kampus Unggulan',
                        'isi' => 'Terus mengembangkan fasilitas, kerja sama industri, dan mutu lulusan.',
                    ],
                ],
                'path_sampul' => null,
                'urutan' => 1,
                'urip' => true,
                'dianyari_dening' => $adminId,
            ],
        ];

        foreach ($items as $item) {
            Sejarah::query()->create($item);
        }
    }
}
