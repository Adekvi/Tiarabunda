<?php

namespace Database\Seeders\Landing\Pendidikan;

use App\Models\Pendidikan\Akreditasi;
use App\Models\Pendidikan\Program\Fakultas;
use App\Models\Pendidikan\Program\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        $fakultasNames = [
            'Ilmu Kesehatan',
            'Keguruan dan Ilmu Pendidikan',
            'Ilmu Komputer',
            'Ekonomi Bisnis',
        ];

        $fakultasMap = [];
        foreach ($fakultasNames as $i => $nama) {
            $fakultasMap[$i] = Fakultas::query()
                ->where('tandha', Str::slug($nama))
                ->firstOrFail();
        }

        $akrFarmasi     = Akreditasi::where('jeneng', 'Sertifikat Akreditasi D3 Farmasi')->value('id');
        $akrKebidananD3 = Akreditasi::where('jeneng', 'Sertifikat Akreditasi D3 Kebidanan')->value('id');
        $akrKebidananD4 = Akreditasi::where('jeneng', 'Sertifikat Akreditasi Sarjana Terapan Kebidanan')->value('id');
        $akrAnestesi    = Akreditasi::where('jeneng', 'Sertifikat Akreditasi Sarjana Terapan Keperawatan Anestesiologi')->value('id');
        $akrProfesi     = Akreditasi::where('jeneng', 'Sertifikat Akreditasi Profesi Bidan')->value('id');

        $prodis = [
            // === Fakultas Ilmu Kesehatan (0) ===
            [
                'jeneng'        => 'Farmasi',
                'undhak'        => 'D3',
                'fakultas'      => 0,
                'akreditasi_id' => $akrFarmasi,
                'katrangan'     => 'Program studi vokasi yang membekali mahasiswa dengan kompetensi pelayanan kefarmasian, peracikan dan formulasi sediaan obat, pengelolaan perbekalan farmasi, serta pengendalian mutu di apotek, rumah sakit, puskesmas, maupun industri farmasi.',
            ],
            [
                'jeneng'        => 'Kebidanan',
                'undhak'        => 'D3',
                'fakultas'      => 0,
                'akreditasi_id' => $akrKebidananD3,
                'katrangan'     => 'Mencetak tenaga ahli madya kebidanan yang terampil dan humanis dalam memberikan asuhan kebidanan esensial pada masa kehamilan, persalinan, nifas, bayi baru lahir, serta pelayanan kesehatan reproduksi dan keluarga berencana.',
            ],
            [
                'jeneng'        => 'Keperawatan Bidan Anestesiologi',
                'undhak'        => 'D4',
                'fakultas'      => 0,
                'akreditasi_id' => $akrAnestesi,
                'katrangan'     => 'Program sarjana terapan yang menyiapkan penata anestesi profesional dengan keahlian asuhan kepenataan pra, intra, dan pascaanestesi serta penanganan kegawatdaruratan medis di ruang operasi dan unit perawatan intensif.',
            ],
            [
                'jeneng'        => 'Kebidanan',
                'undhak'        => 'D4',
                'fakultas'      => 0,
                'akreditasi_id' => $akrKebidananD4,
                'katrangan'     => 'Program sarjana terapan yang berfokus pada asuhan kebidanan komprehensif berbasis bukti ilmiah (evidence-based practice), deteksi dini komplikasi maternal-neonatal, serta manajemen pelayanan kesehatan ibu dan anak.',
            ],
            [
                'jeneng'        => 'Pendidikan Profesi Bidan',
                'undhak'        => 'Bdn',
                'fakultas'      => 0,
                'akreditasi_id' => $akrProfesi,
                'katrangan'     => 'Program pendidikan profesi lanjutan bagi lulusan sarjana terapan atau sarjana kebidanan untuk memperoleh kewenangan praktik klinis mandiri, kepemimpinan pelayanan kebidanan, serta pengambilan keputusan klinis profesional.',
            ],

            // === Fakultas Keguruan dan Ilmu Pendidikan (1) ===
            [
                'jeneng'        => 'Pendidikan Guru PAUD',
                'undhak'        => 'S1',
                'fakultas'      => 1,
                'akreditasi_id' => null,
                'katrangan'     => 'Menyiapkan pendidik anak usia dini yang kreatif dan inovatif dengan penguasaan psikologi perkembangan anak, perancangan pembelajaran berbasis bermain, stimulasi tumbuh kembang holistik, serta pengelolaan lembaga PAUD.',
            ],
            [
                'jeneng'        => 'Pendidikan Guru Sekolah Dasar (PGSD)',
                'undhak'        => 'S1',
                'fakultas'      => 1,
                'akreditasi_id' => null,
                'katrangan'     => 'Mencetak guru kelas sekolah dasar yang menguasai pedagogi modern, kurikulum pembelajaran terpadu, penguatan literasi dan numerasi, serta pemanfaatan media pembelajaran digital untuk membentuk karakter peserta didik.',
            ],

            // === Fakultas Ilmu Komputer (2) ===
            [
                'jeneng'        => 'Teknologi Perangkat Lunak',
                'undhak'        => 'D4',
                'fakultas'      => 2,
                'akreditasi_id' => null,
                'katrangan'     => 'Program sarjana terapan berorientasi praktik industri yang berfokus pada rekayasa perangkat lunak, pengembangan aplikasi web dan mobile, arsitektur sistem, penjaminan mutu perangkat lunak (QA), serta penerapan Agile dan DevOps.',
            ],
            [
                'jeneng'        => 'Sistem Informasi',
                'undhak'        => 'S1',
                'fakultas'      => 2,
                'akreditasi_id' => null,
                'katrangan'     => 'Mempelajari integrasi teknologi informasi dengan proses bisnis organisasi, analisis dan perancangan sistem informasi, manajemen basis data, tata kelola TI, serta strategi transformasi digital perusahaan.',
            ],
            [
                'jeneng'        => 'Kecerdasan Buatan',
                'undhak'        => 'S1',
                'fakultas'      => 2,
                'akreditasi_id' => null,
                'katrangan'     => 'Berfokus pada pengembangan solusi berbasis Artificial Intelligence yang mencakup machine learning, deep learning, pengolahan bahasa alami (NLP), computer vision, dan rekayasa data cerdas untuk kebutuhan industri modern.',
            ],
            [
                'jeneng'        => 'Ilmu Komputer',
                'undhak'        => 'S1',
                'fakultas'      => 2,
                'akreditasi_id' => null,
                'katrangan'     => 'Membekali mahasiswa dengan fondasi kuat dalam algoritma, struktur data, pemrograman, jaringan komputer, keamanan siber, dan komputasi awan untuk merancang solusi komputasi yang efisien dan skalabel.',
            ],

            // === Fakultas Ekonomi Bisnis (3) ===
            [
                'jeneng'        => 'Logistik Bisnis',
                'undhak'        => 'D4',
                'fakultas'      => 3,
                'akreditasi_id' => null,
                'katrangan'     => 'Program sarjana terapan yang mempelajari manajemen rantai pasok (supply chain management), operasional pergudangan, transportasi dan distribusi, pengadaan barang, serta sistem informasi logistik berbasis standar industri.',
            ],
            [
                'jeneng'        => 'Bisnis Digital',
                'undhak'        => 'D4',
                'fakultas'      => 3,
                'akreditasi_id' => null,
                'katrangan'     => 'Program sarjana terapan yang memadukan strategi manajemen bisnis dengan teknologi digital, meliputi e-commerce, pemasaran digital (digital marketing), analitik bisnis, serta pengembangan startup dan inovasi model bisnis.',
            ],
            [
                'jeneng'        => 'Manajemen',
                'undhak'        => 'S1',
                'fakultas'      => 3,
                'akreditasi_id' => null,
                'katrangan'     => 'Membekali mahasiswa dengan kompetensi kepemimpinan, manajemen keuangan, pemasaran, sumber daya manusia, dan kewirausahaan untuk mengelola dan mengembangkan organisasi maupun usaha secara berkelanjutan.',
            ],
        ];

        foreach ($prodis as $i => $p) {
            $fakultas = $fakultasMap[$p['fakultas']];

            $tandha = Str::slug(
                ($p['undhak'] ? $p['undhak'] . '-' : '') . $p['jeneng']
            );

            Prodi::query()->updateOrCreate(
                [
                    'tandha' => $tandha,
                ],
                [
                    'fakultas_id'   => $fakultas->id,
                    'jeneng'        => $p['jeneng'],
                    'undhak'        => $p['undhak'],
                    'akreditasi_id' => $p['akreditasi_id'],
                    'katrangan'     => $p['katrangan'],
                    'urutan'        => $i + 1,
                    'urip'          => true,
                ]
            );
        }
    }
}
