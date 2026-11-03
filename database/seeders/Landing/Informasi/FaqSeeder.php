<?php

namespace Database\Seeders\Landing\Informasi;

use App\Models\Informasi\Faq;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sudah ada data (termasuk soft-deleted yang masih dihitung jika pakai withTrashed)
        // → jangan ganggu input admin
        if (Faq::query()->withTrashed()->exists()) {
            $this->command?->info('FAQ sudah ada — seeder dilewati.');

            return;
        }

        $rows = config('landing.home.faq', []);

        if ($rows === []) {
            $rows = [
                [
                    'Bagaimana cara mendaftar?',
                    'Isi email di formulir PMB, lengkapi data diri, unggah dokumen, lalu ikuti seleksi sesuai jalur yang dipilih.',
                ],
                [
                    'Apakah kampus sudah terakreditasi?',
                    'Informasi akreditasi institusi dan program studi tersedia di menu Akreditasi.',
                ],
                [
                    'Apakah ada kelas karyawan?',
                    'Ada kelas sore untuk program studi tertentu. Cek detailnya di halaman PMB.',
                ],
                [
                    'Bagaimana pembayaran biaya kuliah?',
                    'Pembayaran lewat virtual account dan dapat dicicil sesuai ketentuan. Rinciannya ada di halaman Biaya.',
                ],
            ];
        }

        foreach ($rows as $i => $row) {
            // Support format config [pertanyaan, jawaban]
            $pitakon = is_array($row) ? ($row[0] ?? '') : '';
            $wangsulan = is_array($row) ? ($row[1] ?? '') : '';

            if ($pitakon === '' || $wangsulan === '') {
                continue;
            }

            Faq::query()->create([
                'pitakon' => $pitakon,
                'wangsulan' => $wangsulan,
                'urutan' => $i,
                'urip' => true,
            ]);
        }

        $this->command?->info('FAQ default berhasil di-seed (' . count($rows) . ' baris).');
    }
}
