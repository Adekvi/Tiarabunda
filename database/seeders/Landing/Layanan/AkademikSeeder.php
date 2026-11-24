<?php

namespace Database\Seeders\Landing\Layanan;

use App\Models\Layanan\Akademik;
use App\Services\Landing\Layanan\AkademikService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class AkademikSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'kode'           => 'FM-SN.DIK-07-17',
                'jeneng'         => 'Formulir Permohonan Surat Keterangan Aktif Kuliah',
                'tandha'         => 'fm-sn-dik-07-17-surat-keterangan-aktif-kuliah',
                'kategori'       => 'Surat Keterangan',
                'katrangan'      => 'Kode Dokumen: FM-SN.DIK-07-17. Digunakan untuk pengajuan surat keterangan aktif kuliah guna keperluan tunjangan orang tua, BPJS, beasiswa, maupun administrasi instansi.',
                'fallback_file'  => 'FM-SN.DIK-07-17 FORMULIR PERMOHONAN SURAT KETERANGAN AKTIF KULIAH.docx',
                'nama_file_asli' => 'FM-SN.DIK-07-17_Formulir_Surat_Keterangan_Aktif_Kuliah.docx',
                'urutan'         => 1,
                'urip'           => true,
            ],
            [
                'kode'           => 'FM-SN.DIK-07-18',
                'jeneng'         => 'Formulir Permohonan Cuti Akademik',
                'tandha'         => 'fm-sn-dik-07-18-permohonan-cuti-akademik',
                'kategori'       => 'Cuti & Studi',
                'katrangan'      => 'Kode Dokumen: FM-SN.DIK-07-18. Formulir permohonan berhenti studi sementara (cuti kuliah) yang ditandatangani pemohon dan diketahui oleh Ketua Program Studi.',
                'fallback_file'  => 'FM-SN.DIK-07-18 FORMULIR PERMOHONAN CUTI KULIAH.doc',
                'nama_file_asli' => 'FM-SN.DIK-07-18_Formulir_Permohonan_Cuti_Akademik.doc',
                'urutan'         => 2,
                'urip'           => true,
            ],
            [
                'kode'           => 'FM-SN.DIK-07-11',
                'jeneng'         => 'Formulir Permohonan Surat Izin Penelitian',
                'tandha'         => 'fm-sn-dik-07-11-surat-izin-penelitian',
                'kategori'       => 'Penelitian & Tugas Akhir',
                'katrangan'      => 'Kode Dokumen: FM-SN.DIK-07-11. Pengajuan surat pengantar izin penelitian ke instansi/lahan praktik dengan persetujuan Dosen Pembimbing.',
                'fallback_file'  => 'FM-SN.DIK-07-11 FORMULIR PERMOHONAN SURAT IZIN PENELITIAN.docx',
                'nama_file_asli' => 'FM-SN.DIK-07-11_Formulir_Permohonan_Surat_Izin_Penelitian.docx',
                'urutan'         => 3,
                'urip'           => true,
            ],
            [
                'kode'           => 'FM-SN.DIK-07-20',
                'jeneng'         => 'Formulir Permohonan Surat Studi Pendahuluan',
                'tandha'         => 'fm-sn-dik-07-20-surat-studi-pendahuluan',
                'kategori'       => 'Penelitian & Tugas Akhir',
                'katrangan'      => 'Kode Dokumen: FM-SN.DIK-07-20. Formulir pengajuan surat pengantar pengambilan data awal / studi pendahuluan di instansi tujuan untuk penyusunan Tugas Akhir.',
                'fallback_file'  => 'FM-SN.DIK-07-20 FORMULIR PERMOHONAN SURAT STUDI PENDAHULUAN.docx',
                'nama_file_asli' => 'FM-SN.DIK-07-20_Formulir_Permohonan_Surat_Studi_Pendahuluan.docx',
                'urutan'         => 4,
                'urip'           => true,
            ],
        ];

        Akademik::withTrashed()
            ->whereIn('tandha', collect($items)->pluck('tandha')->all())
            ->forceDelete();

        $baseDir = public_path('seed/akademik');

        foreach ($items as $item) {
            // Cari otomatis berdasarkan awalan kode dokumen (misal: FM-SN.DIK-07-20*.doc*)
            $matches = File::glob($baseDir . DIRECTORY_SEPARATOR . $item['kode'] . '*.doc*');
            $actualFileName = ! empty($matches)
                ? basename($matches[0])
                : $item['fallback_file'];

            $relativePath = 'seed/akademik/' . $actualFileName;

            if (! File::exists(public_path($relativePath))) {
                $this->command?->warn("File master tidak ditemukan untuk kode: {$item['kode']}");
            }

            Akademik::query()->create([
                'jeneng'         => $item['jeneng'],
                'tandha'         => $item['tandha'],
                'kategori'       => $item['kategori'],
                'katrangan'      => $item['katrangan'],
                'path_file'      => $relativePath,
                'nama_file_asli' => $item['nama_file_asli'],
                'urutan'         => $item['urutan'],
                'urip'           => $item['urip'],
            ]);
        }

        Cache::forget(AkademikService::CACHE_KEY);
        $this->command?->info('✔ ' . count($items) . ' formulir akademik berhasil dimuat dari public/seed/akademik.');
    }
}
