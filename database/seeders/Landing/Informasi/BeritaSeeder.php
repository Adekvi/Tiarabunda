<?php

namespace Database\Seeders\Landing\Informasi;

use App\Models\Informasi\Berita;
use App\Models\User;
use App\Services\Media\ImageOptimizer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::query()->value('id');
        $disk = Storage::disk('public');
        $optimizer = app(ImageOptimizer::class);

        $disk->makeDirectory('berita');

        foreach ($this->data() as $row) {
            $existing = Berita::withTrashed()
                ->where('tandha', $row['tandha'])
                ->first();

            $paths = [
                'path_sampul' => $existing?->path_sampul,
                'path_thumb'  => $existing?->path_thumb,
            ];

            $hasPhysicalFile = filled($paths['path_sampul']) && $disk->exists($paths['path_sampul']);

            if (! $hasPhysicalFile) {
                $imagePath = $this->resolveSeedImage($row['gambar'] ?? null);

                if ($imagePath) {
                    try {
                        $fixedName = 'seed-' . pathinfo($imagePath, PATHINFO_FILENAME);

                        $stored = $optimizer->storeFromPath(
                            sourcePath: $imagePath,
                            folder: 'berita',
                            fixedName: $fixedName,
                            withThumb: false,
                        );

                        $paths = [
                            'path_sampul' => $stored['path'],
                            'path_thumb'  => $stored['thumb'], // null (single file WebP)
                        ];
                    } catch (\Throwable $e) {
                        $this->command?->error("✗ Gagal proses gambar {$row['judhul']}: {$e->getMessage()}");
                    }
                }
            }

            $payload = collect($row)
                ->except('gambar')
                ->merge($paths)
                ->merge([
                    'digawe_dening' => $existing?->digawe_dening ?? $adminId,
                    'deleted_at'    => null,
                ])
                ->all();

            if ($existing) {
                $existing->update($payload);
                $this->command?->info("↻ Update: {$row['judhul']}");
            } else {
                Berita::query()->create($payload);
                $this->command?->info("✓ Create: {$row['judhul']}");
            }
        }

        $this->cleanupOrphanFiles();

        $this->command?->newLine();
        $this->command?->info('Selesai. Total berita: ' . Berita::withTrashed()->count());
    }

    /**
     * Hapus file di storage/app/public/berita yang tidak lagi tercatat di tabel beritas.
     */
    private function cleanupOrphanFiles(): void
    {
        $disk = Storage::disk('public');

        $usedPaths = Berita::withTrashed()
            ->get(['path_sampul', 'path_thumb'])
            ->toBase()
            ->flatMap(fn(Berita $item): array => [$item->path_sampul, $item->path_thumb])
            ->filter()
            ->values()
            ->all();

        $orphans = array_values(array_diff($disk->files('berita'), $usedPaths));

        if ($orphans !== []) {
            $disk->delete($orphans);
            $this->command?->warn('🧹 Membersihkan ' . count($orphans) . ' file gambar yatim di folder berita.');
        }
    }

    /**
     * Cari file gambar sumber di public/seed/berita/.
     */
    private function resolveSeedImage(?string $filename): ?string
    {
        if (blank($filename)) {
            return null;
        }

        $candidates = [
            public_path('seed/berita/' . $filename),
            public_path('seed/' . $filename),
        ];

        foreach ($candidates as $path) {
            if (File::isFile($path)) {
                return $path;
            }
        }

        $this->command?->warn("  ⚠ File gambar tidak ditemukan: {$filename}");

        return null;
    }

    private function data(): array
    {
        return [
            [
                'judhul'       => 'Visitasi Tempat Uji Kompetensi Program Studi Sarjana Terapan Keperawatan Anestesiologi Politeknik Tiara Bunda',
                'tandha'       => Str::slug('Visitasi Tempat Uji Kompetensi Program Studi Sarjana Terapan Keperawatan Anestesiologi Politeknik Tiara Bunda') . '-3',
                'golongan'     => 'Kampus',
                'ringkesan'    => 'Politeknik Tiara Bunda melaksanakan kegiatan Visitasi Tempat Uji Kompetensi (TUK) Program Studi Sarjana Terapan Keperawatan Anestesiologi pada Jumat, 11 September 2026.',
                'isi'          => <<<TEXT
Depok, 11 September 2026 — Politeknik Tiara Bunda melaksanakan kegiatan Visitasi Tempat Uji Kompetensi (TUK) Program Studi Sarjana Terapan Keperawatan Anestesiologi pada Jumat, 11 September 2026. Kegiatan ini menjadi salah satu bagian penting dalam memastikan kesiapan sarana, prasarana, serta penyelenggaraan uji kompetensi yang sesuai dengan standar yang telah ditetapkan.

Visitasi dilaksanakan oleh Ibu Dr. Ns. Rahmaya Nova Handayani, S.Tr.Kes., S.Kep., M.Sc., M.M., Sp.Kep.MB. dan Bapak Dr. Erindra Budi Cahyanto, S.Tr.Kes., S.Kep., Ns., M.Kes. Dalam pelaksanaannya, kegiatan tersebut didampingi oleh Ketua Program Studi Sarjana Terapan Keperawatan Anestesiologi beserta seluruh dosen Keperawatan Anestesiologi Politeknik Tiara Bunda.

Kegiatan visitasi mencakup peninjauan dan penilaian terhadap kesiapan tempat uji kompetensi, baik dari aspek fasilitas, sarana dan prasarana, maupun dukungan penyelenggaraan uji kompetensi. Proses ini diharapkan dapat memastikan bahwa TUK Program Studi Sarjana Terapan Keperawatan Anestesiologi Politeknik Tiara Bunda mampu mendukung pelaksanaan uji kompetensi secara optimal, profesional, dan sesuai dengan ketentuan yang berlaku.

Melalui kegiatan visitasi ini, Politeknik Tiara Bunda terus menunjukkan komitmennya dalam meningkatkan mutu pendidikan dan kompetensi lulusan, khususnya pada bidang Keperawatan Anestesiologi. Kesiapan TUK menjadi bagian penting dalam mendukung proses evaluasi kompetensi mahasiswa sehingga lulusan yang dihasilkan memiliki kompetensi, profesionalisme, dan kesiapan untuk memberikan pelayanan kesehatan yang berkualitas.

Politeknik Tiara Bunda berharap kegiatan visitasi ini dapat menjadi langkah positif dalam penguatan kualitas penyelenggaraan pendidikan dan uji kompetensi, sekaligus mendukung terwujudnya lulusan Keperawatan Anestesiologi yang kompeten, profesional, mandiri, dan mampu bersaing di dunia kerja.

Politeknik Tiara Bunda — Kampus Kesehatan, Mencetak Tenaga Kesehatan Profesional dan Kompeten.
TEXT,
                'gambar'       => 'berita2.webp',
                'wektu_terbit' => '2026-09-11 09:00:00',
                'urutan'       => 2,
                'urip'         => true,
            ],
            [
                'judhul'       => 'PKKMB Politeknik Tiara Bunda 2026: From New Student to Future Professional',
                'tandha'       => Str::slug('PKKMB Politeknik Tiara Bunda 2026: From New Student to Future Professional') . '-2',
                'golongan'     => 'Akademik',
                'ringkesan'    => 'Politeknik Tiara Bunda resmi membuka kegiatan Pengenalan Kehidupan Kampus bagi Mahasiswa Baru (PKKMB) Tahun 2026 pada Selasa, 15 September 2026.',
                'isi'          => <<<TEXT
Depok, Selasa, 15 September 2026 — Politeknik Tiara Bunda resmi membuka kegiatan Pengenalan Kehidupan Kampus bagi Mahasiswa Baru (PKKMB) Tahun 2026 pada Selasa, 15 September 2026. Kegiatan PKKMB tahun ini mengusung tema “From New Student to Future Professional: Building Character, Competence & Collaboration.”

Pembukaan PKKMB berlangsung dengan penuh semangat dan menjadi momentum awal bagi mahasiswa baru untuk mengenal lingkungan kampus, membangun karakter, meningkatkan kompetensi, serta menjalin kolaborasi sebagai bekal dalam menjalani kehidupan akademik di Politeknik Tiara Bunda.

Kegiatan pembukaan secara resmi dibuka oleh Direktur Politeknik Tiara Bunda, Dr. Bdn. Lusy Pratiwi, S.Tr.Keb., M.K.M. Turut hadir Ketua Yayasan Cerdas Mutiara Bangsa, seluruh Dosen dan Civitas Akademika Politeknik Tiara Bunda, serta BEM Politeknik Tiara Bunda.

Dalam kesempatan tersebut, mahasiswa baru diperkenalkan dengan berbagai aspek kehidupan kampus, mulai dari lingkungan akademik, tata tertib, kegiatan kemahasiswaan, hingga berbagai nilai yang menjadi bagian penting dalam membentuk mahasiswa yang profesional dan berkarakter.

PKKMB Politeknik Tiara Bunda berlangsung selama tiga hari, yaitu 15–17 September 2026. Selama kegiatan, mahasiswa baru akan mendapatkan berbagai materi dan pengalaman yang dirancang untuk membantu mereka beradaptasi dengan lingkungan perguruan tinggi sekaligus mempersiapkan diri menghadapi proses pembelajaran di masing-masing program studi.

Melalui tema “From New Student to Future Professional”, Politeknik Tiara Bunda berharap seluruh mahasiswa baru dapat menjadikan PKKMB sebagai langkah awal untuk terus berkembang, membangun karakter positif, meningkatkan kompetensi, serta mampu berkolaborasi dengan sesama dalam mewujudkan generasi profesional di bidang kesehatan.

Selamat datang dan selamat mengikuti PKKMB 2026, mahasiswa baru Politeknik Tiara Bunda!
Mari bersama tumbuh, belajar, berkolaborasi, dan bersiap menjadi Future Professionals! 🎓✨
TEXT,
                'gambar'       => 'berita1.webp',
                'wektu_terbit' => '2026-09-15 10:00:00',
                'urutan'       => 3,
                'urip'         => true,
            ],
            [
                'judhul'       => 'OSCE D4 Kebidanan Semester 8',
                'tandha'       => Str::slug('POSCE D4 Kebidanan Semester 8') . '-1',
                'golongan'     => 'Kampus',
                'ringkesan'    => 'Mahasiswa Program Studi D4 Kebidanan Semester 8 Politeknik Tiara Bunda mengikuti Objective Structured Clinical Examination (OSCE)',
                'isi'          => <<<TEXT
Depok, Jumat, 14 Agustus 2026 — Mahasiswa Program Studi D4 Kebidanan Semester 8 Politeknik Tiara Bunda mengikuti Objective Structured Clinical Examination (OSCE) sebagai salah satu bentuk evaluasi untuk mengukur kompetensi dan keterampilan klinis mahasiswa dalam memberikan pelayanan kebidanan.
Pelaksanaan OSCE dilakukan melalui beberapa tahapan atau station yang dirancang untuk menguji kemampuan mahasiswa dalam menerapkan pengetahuan dan keterampilan yang telah diperoleh selama proses pembelajaran. Dalam pelaksanaannya, mahasiswa dituntut untuk menunjukkan ketelitian, ketepatan prosedur, komunikasi yang baik, serta sikap profesional dalam menghadapi berbagai situasi klinis.
Kegiatan ini menjadi bagian penting dalam mempersiapkan mahasiswa D4 Kebidanan Semester 8 untuk memasuki dunia pelayanan kebidanan secara profesional. Melalui OSCE, mahasiswa diharapkan mampu mengintegrasikan pengetahuan, keterampilan, dan sikap dalam memberikan pelayanan yang aman, berkualitas, dan sesuai dengan standar profesi.
Dengan mengikuti OSCE, mahasiswa juga mendapatkan pengalaman dalam menghadapi situasi yang menyerupai kondisi pelayanan di lapangan. Hal ini diharapkan dapat meningkatkan kepercayaan diri serta kesiapan mahasiswa dalam menjalankan peran sebagai calon tenaga kebidanan yang kompeten dan profesional.
Politeknik Tiara Bunda terus berkomitmen dalam memberikan pendidikan kesehatan yang berkualitas melalui proses pembelajaran dan evaluasi yang terarah, guna mencetak lulusan yang kompeten, profesional, berintegritas, dan siap memberikan pelayanan terbaik bagi masyarakat.

✨ Sukses untuk seluruh mahasiswa D4 Kebidanan Semester 8! Terus berproses, terus belajar, dan berikan yang terbaik! 🩺👩‍⚕️
TEXT,
                'gambar'       => 'berita3.webp',
                'wektu_terbit' => '2026-08-14 08:00:00',
                'urutan'       => 3,
                'urip'         => true,
            ],
            [
                'judhul'       => 'Politeknik Tiara Bunda Gelar Jalan Sehat Bersama Civitas Akademika dan Mahasiswa',
                'tandha'       => Str::slug('Politeknik Tiara Bunda Gelar Jalan Sehat Bersama Civitas Akademika dan Mahasiswa') . '-4',
                'golongan'     => 'Kegiatan',
                'ringkesan'    => 'Dalam rangka memeriahkan HUT ke-81 Kemerdekaan RI, Politeknik Tiara Bunda menggelar Jalan Sehat Semarak Kemerdekaan Indonesia pada Minggu, 16 Agustus 2026.',
                'isi'          => <<<TEXT
Depok, 16 Agustus 2026 — Dalam rangka memeriahkan HUT ke-81 Kemerdekaan Republik Indonesia, Politeknik Tiara Bunda menggelar kegiatan Jalan Sehat Semarak Kemerdekaan Indonesia pada Minggu, 16 Agustus 2026.

Kegiatan ini diikuti dengan penuh antusias oleh seluruh Civitas Akademika dan mahasiswa Politeknik Tiara Bunda. Suasana kebersamaan dan semangat kemerdekaan terasa sepanjang kegiatan, dengan para peserta mengikuti jalan sehat secara bersama-sama.

Jalan sehat tidak hanya menjadi bagian dari rangkaian perayaan kemerdekaan, tetapi juga menjadi momentum untuk membangun kesadaran akan pentingnya pola hidup sehat dan menjaga kebugaran tubuh. Selain itu, kegiatan ini turut mempererat tali silaturahmi, kekeluargaan, dan kekompakan seluruh keluarga besar Politeknik Tiara Bunda.

Melalui kegiatan ini, Politeknik Tiara Bunda terus menanamkan semangat sehat, aktif, kompak, dan produktif kepada seluruh civitas akademika. Semangat kemerdekaan diharapkan dapat terus diwujudkan melalui kegiatan positif, prestasi, pendidikan, serta pengabdian kepada masyarakat.

Kegiatan Jalan Sehat Semarak Kemerdekaan Indonesia menjadi salah satu bentuk nyata kebersamaan keluarga besar Politeknik Tiara Bunda dalam menyambut HUT ke-81 Republik Indonesia.

🇮🇩 Dirgahayu Republik Indonesia ke-81!

Sehat bersama, kompak bersama, dan terus berkarya untuk Indonesia.
Merdeka! 🇮🇩
TEXT,
                'gambar'       => 'berita4.webp',
                'wektu_terbit' => '2026-08-16 07:00:00',
                'urutan'       => 4,
                'urip'         => true,
            ],
            [
                'judhul'       => 'Politeknik Tiara Bunda Gelar Upacara Peringatan HUT ke-81 Kemerdekaan Republik Indonesia',
                'tandha'       => Str::slug('Politeknik Tiara Bunda Gelar Upacara Peringatan HUT ke-81 Kemerdekaan Republik Indonesia') . '-5',
                'golongan'     => 'Kegiatan',
                'ringkesan'     => 'Politeknik Tiara Bunda melaksanakan upacara peringatan HUT ke-81 Kemerdekaan RI pada Senin, 17 Agustus 2026 dengan penuh khidmat.',
                'isi'          => <<<TEXT
Depok, 17 Agustus 2026 — Dalam rangka memperingati Hari Ulang Tahun (HUT) ke-81 Kemerdekaan Republik Indonesia, Politeknik Tiara Bunda melaksanakan upacara peringatan kemerdekaan pada Senin, 17 Agustus 2026. Kegiatan berlangsung dengan penuh khidmat dan diikuti oleh seluruh Civitas Akademika Politeknik Tiara Bunda serta seluruh mahasiswa.

Upacara menjadi momentum untuk mengenang jasa para pahlawan sekaligus menumbuhkan semangat nasionalisme, persatuan, dan cinta Tanah Air di lingkungan pendidikan tinggi. Seluruh peserta mengikuti rangkaian upacara dengan tertib dan penuh penghormatan.

Upacara semakin istimewa dengan kehadiran Ketua Yayasan Cerdas Mutiara Bangsa selaku Pembina Upacara. Dalam amanatnya, beliau menyampaikan pentingnya memaknai kemerdekaan melalui semangat belajar, berkarya, berprestasi, serta memberikan kontribusi nyata bagi masyarakat dan bangsa.

Bagi insan pendidikan kesehatan, semangat kemerdekaan juga menjadi pengingat akan pentingnya mencetak generasi yang tidak hanya unggul secara akademik, tetapi juga memiliki karakter, integritas, kepedulian, dan jiwa pengabdian.

Peringatan HUT ke-81 Kemerdekaan Republik Indonesia di Politeknik Tiara Bunda diharapkan dapat menjadi momentum untuk terus memperkuat semangat kebersamaan dan meningkatkan kontribusi seluruh civitas akademika dalam membangun Indonesia yang sehat, maju, dan berkualitas.

Dirgahayu Republik Indonesia ke-81!
Terus belajar, terus berkarya, dan terus mengabdi untuk Indonesia.
Merdeka! 🇮🇩
TEXT,
                'gambar'       => 'berita5.webp',
                'wektu_terbit' => '2026-08-17 08:00:00',
                'urutan'       => 5,
                'urip'         => true,
            ],
        ];
    }
}
