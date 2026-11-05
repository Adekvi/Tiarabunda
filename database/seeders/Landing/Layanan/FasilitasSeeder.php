<?php

namespace Database\Seeders\Landing\Layanan;

use App\Models\Layanan\Fasilitas;
use App\Services\Media\ImageOptimizer;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Str;

class FasilitasSeeder extends Seeder
{

    public function run(): void
    {
        $items = $this->data();

        Storage::disk('public')->makeDirectory('fasilitas');

        foreach ($items as $i => $row) {
            $fasilitas = Fasilitas::withTrashed()
                ->where('jeneng', $row['jeneng'])
                ->first();

            $data = [
                'jeneng'    => $row['jeneng'],
                'katrangan' => $row['katrangan'] ?? null,
                'urutan'    => $row['urutan'] ?? $i + 1,
                'urip'      => $row['urip'] ?? true,
            ];

            // Proses gambar (baik untuk create maupun update jika path_gambar masih null)
            $needsImage = ! $fasilitas || blank($fasilitas->path_gambar);

            if ($needsImage) {
                $imagePath = $this->resolveSeedImage($row['gambar'] ?? null);

                if ($imagePath) {
                    try {
                        $stored = app(ImageOptimizer::class)->store(
                            new UploadedFile(
                                $imagePath,
                                basename($imagePath),
                                mime_content_type($imagePath) ?: 'image/webp',
                                null,
                                true
                            ),
                            'fasilitas'
                        );

                        $data['path_gambar'] = $stored['path'];
                        $data['path_thumb']  = $stored['thumb'];
                    } catch (\Throwable $e) {
                        $this->command->error("✗ Gagal proses gambar {$row['jeneng']}: {$e->getMessage()}");
                    }
                }
            }

            if ($fasilitas) {
                $fasilitas->update($data);
                $this->command->info("↻ Update: {$row['jeneng']}" . (isset($data['path_gambar']) ? ' (gambar ditambahkan)' : ''));
            } else {
                Fasilitas::create($data);
                $this->command->info("✓ Create: {$row['jeneng']}");
            }
        }

        $this->command->newLine();
        $this->command->info('Selesai. Total fasilitas: ' . Fasilitas::count());
    }

    /**
     * Cari file gambar di public/seed/fasilitas/.
     * Return null jika tidak ada.
     */
    private function resolveSeedImage(?string $filename): ?string
    {
        if (blank($filename)) {
            return null;
        }

        $candidates = [
            public_path('seed/fasilitas/' . $filename),
            public_path('seed/' . $filename),
        ];

        foreach ($candidates as $path) {
            if (File::isFile($path)) {
                return $path;
            }
        }

        $this->command->warn("  ⚠ File gambar tidak ditemukan: {$filename}");

        return null;
    }

    /**
     * Data fasilitas kampus.
     * Urutan array = urutan tampil default.
     */
    private function data(): array
    {
        return [
            [
                'jeneng'    => 'Laboratorium Farmakognosi',
                'katrangan' => '<p>Laboratorium Farmakognosi mempelajari berbagai aspek bahan alam, khususnya yang berasal dari tumbuhan, hewan, maupun sumber alam lainnya, yang dapat digunakan sebagai bahan obat atau bahan pendukung dalam pengembangan produk kefarmasian. Melalui kegiatan praktikum, mahasiswa tidak hanya mempelajari teori, tetapi juga mendapatkan pengalaman dalam mengidentifikasi, mengamati, mengolah, dan menganalisis bahan alam berdasarkan karakteristik dan sifatnya.</p>',
                'gambar'    => 'fas1.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Laboratorium Teknologi Farmasi',
                'katrangan' => '<p>Laboratorium Teknologi Farmasi memiliki peran penting dalam menghasilkan sediaan yang tidak hanya mengandung bahan aktif, tetapi juga memiliki karakteristik yang mendukung mutu, stabilitas, keamanan, dan kemudahan penggunaan. Laboratorium ini menjadi sarana bagi mahasiswa untuk mengintegrasikan pengetahuan ilmu kefarmasian, keterampilan teknis, ketelitian, dan kemampuan analisis melalui kegiatan pembelajaran berbasis praktik.</p>',
                'gambar'    => 'fas2.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Laboratorium Mikrobiologi',
                'katrangan' => '<p>Mikrobiologi memiliki hubungan yang erat dengan bidang kefarmasian. Pemahaman mengenai mikroorganisme dibutuhkan dalam berbagai aspek, termasuk pengendalian kontaminasi, pengujian mikrobiologi, keamanan produk, serta pemahaman terhadap mikroorganisme yang berhubungan dengan kesehatan. Mahasiswa tidak hanya menerima materi secara teoritis, tetapi juga memperoleh kesempatan untuk mengamati, melakukan praktik, mencatat hasil, menganalisis data, dan menyusun kesimpulan berdasarkan kegiatan yang dilakukan.</p>',
                'gambar'    => 'fas3.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Laboratorium Farmasetika',
                'katrangan' => '<p>Laboratorium Farmasetika merupakan fasilitas pembelajaran yang mendukung mahasiswa Program Studi Farmasi dalam memahami dan mempraktikkan proses pembuatan berbagai bentuk sediaan farmasi. Laboratorium ini menjadi tempat mahasiswa mengintegrasikan pengetahuan teori dengan keterampilan praktis dalam pengolahan bahan menjadi sediaan farmasi yang memenuhi persyaratan mutu. Melalui kegiatan praktikum, mahasiswa tidak hanya mempelajari cara membuat sediaan obat, tetapi juga memahami prinsip ketepatan dosis, homogenitas, stabilitas, keamanan, kenyamanan penggunaan, serta kualitas sediaan farmasi.</p>',
                'gambar'    => 'fas4.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Laboratorium Kimia Farmasi',
                'katrangan' => '<p>Laboratorium Kimia Farmasi, mahasiswa Farmasi belajar memahami sifat dan karakteristik bahan obat melalui berbagai kegiatan praktikum. Mulai dari identifikasi senyawa, analisis bahan, hingga pengujian secara kualitatif maupun kuantitatif. Praktikum di laboratorium melatih mahasiswa untuk menjadi lebih teliti, disiplin, terampil, dan memahami pentingnya ketepatan dalam dunia kefarmasian. </p>',
                'gambar'    => 'fas5.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Laboratorium Konseling Kebidanan',
                'katrangan' => '<p>Laboratorium Konseling Kebidanan, mahasiswa berlatih membangun komunikasi yang efektif melalui simulasi konseling, mulai dari menggali kebutuhan pasien, memberikan informasi, memberikan edukasi, hingga membantu pasien dalam mengambil keputusan yang tepat.</p>',
                'gambar'    => 'fas6.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Tempat Bank Alat Kebidanan',
                'katrangan' => '<p>Bank Alat Kebidanan merupakan salah satu fasilitas pendukung pembelajaran mahasiswa Kebidanan di Politeknik Tiara Bunda. Tempat ini menjadi ruang penyimpanan sekaligus pengelolaan berbagai alat praktik kebidanan yang digunakan mahasiswa untuk menunjang kegiatan pembelajaran dan praktikum. Dengan fasilitas yang tersedia, mahasiswa dapat lebih mudah mengenal alat, memahami fungsi, dan mempersiapkan kebutuhan praktik sebelum melakukan pembelajaran di laboratorium maupun lahan praktik.</p>',
                'gambar'    => 'fas7.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Laboratorium Bayi Balita Prasekolah (Kebidanan)',
                'katrangan' => '<p>Laboratorium Bayi, Balita & Prasekolah, mahasiswa Kebidanan mendapatkan kesempatan untuk belajar dan berlatih memberikan asuhan pada anak secara menyeluruh. Mulai dari pemantauan pertumbuhan, perkembangan, kebutuhan dasar anak, hingga edukasi kesehatan bagi orang tua. Melalui praktik dan simulasi di laboratorium, mahasiswa juga dilatih untuk lebih teliti, komunikatif, terampil, dan percaya diri dalam memberikan pelayanan kepada bayi, balita, hingga anak prasekolah</p>',
                'gambar'    => 'fas8.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Laboratorium PNC Kebidanan',
                'katrangan' => '<p>Lab PNC (Postnatal Care), mahasiswa berlatih memberikan asuhan masa nifas, memantau kondisi ibu dan bayi, serta memberikan edukasi terkait menyusui dan perawatan bayi.</p>',
                'gambar'    => 'fas9.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Laboratorium Komplementer Kebidanan',
                'katrangan' => '<p>Laboratorium Komplementer Kebidanan, mahasiswa belajar mengembangkan keterampilan untuk memberikan asuhan kebidanan yang holistik dan berpusat pada kebutuhan ibu dan anak. Di sini mahasiswa dapat berlatih berbagai keterampilan terapi komplementer yang dipelajari dalam kebidanan, sekaligus mengasah ketelitian, komunikasi, kreativitas, dan kepercayaan diri dalam memberikan pelayanan.</p>',
                'gambar'    => 'fas10.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Laboratorium OSCE Kebidanan',
                'katrangan' => '<p>Di Laboratorium OSCE Kebidanan, mahasiswa berlatih menghadapi berbagai station/kasus kebidanan dengan mengutamakan ketepatan prosedur, komunikasi, keselamatan pasien, dan profesionalisme. OSCE bukan sekadar ujian, tetapi menjadi wadah bagi mahasiswa untuk mengasah keterampilan praktik kebidanan secara langsung!</p>',
                'gambar'    => 'fas11.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Laboratorium INC Kebidanan',
                'katrangan' => '<p>Di Laboratorium INC (Intranatal Care) Kebidanan, mahasiswa belajar dan berlatih memberikan asuhan pada proses persalinan secara terampil dan profesional. Mulai dari persiapan persalinan, pemantauan kondisi ibu dan janin, hingga praktik pertolongan persalinan, semua diasah melalui pembelajaran dan simulasi.</p>',
                'gambar'    => 'fas12.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Laboratorium ANC Kebidanan',
                'katrangan' => '<p>Di Laboratorium ANC (Antenatal Care) Kebidanan, mahasiswa belajar berbagai keterampilan penting dalam memberikan asuhan kepada ibu hamil, mulai dari anamnesis, pemeriksaan tanda-tanda vital, pemeriksaan fisik, pengukuran tinggi fundus uteri, hingga pemantauan kondisi ibu dan janin.</p>',
                'gambar'    => 'fas13.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Ruang Simulasi Uji Kompetensi (OSCE) D4 Keperawatan Anestesiologi',
                'katrangan' => '<p>Kampus menyediakan fasilitas simulasi untuk persiapan Uji Kompetensi (UKOM) dan OSCE. Mahasiswa dapat berlatih keterampilan klinis melalui skenario yang menyerupai kondisi pelayanan kesehatan sebenarnya.</p>',
                'gambar'    => 'fas14.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Laboratorium Keperawatan Anestesiologi',
                'katrangan' => '<p>Fasilitas ini digunakan oleh mahasiswa Program Sarjana Terapan Keperawatan Anestesiologi untuk mempelajari prosedur anestesi, simulasi tindakan medis, dan keterampilan keperawatan khusus secara praktis.</p>',
                'gambar'    => 'fas15.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Laboratorium Farmasi',
                'katrangan' => '<p>Sebagai kampus yang memiliki Program Studi D3 Farmasi, Politeknik Tiara Bunda menyediakan laboratorium farmasi untuk mendukung pembelajaran praktikum, peracikan obat, dan pengujian sediaan farmasi.</p>',
                'gambar'    => 'fas16.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Lahan Praktik Rumah Sakit dan Puskesmas Mitra',
                'katrangan' => '<p>Politeknik Tiara Bunda bekerja sama dengan rumah sakit, puskesmas, dan praktik mandiri bidan sebagai tempat praktik mahasiswa. Fasilitas ini memberikan pengalaman langsung dalam menangani kasus kesehatan nyata.</p>',
                'gambar'    => 'fas17.webp',
                'urip'      => true,
            ],
            [
                'jeneng'    => 'Laboratorium Kebidanan',
                'katrangan' => '<p>Laboratorium kebidanan dilengkapi dengan berbagai alat praktik seperti phantom, alat pemeriksaan kehamilan (ANC), delivery bed, inkubator, dan peralatan klinik lainnya. Fasilitas ini digunakan untuk melatih keterampilan mahasiswa sebelum terjun ke lapangan praktik.</p>',
                'gambar'    => 'fas18.webp',
                'urip'      => true,
            ],
        ];
    }
}
