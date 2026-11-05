<?php

namespace Database\Seeders\Landing\Pendidikan;

use App\Models\Pendidikan\Program\Fakultas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FakultasSeeder extends Seeder
{
    public function run(): void
    {
        $fakultas = [
            'Ilmu Kesehatan',
            'Keguruan dan Ilmu Pendidikan',
            'Ilmu Komputer',
            'Ekonomi Bisnis',
        ];

        $kodhe = [
            'Ilmu Kesehatan'                 => 'FIK',
            'Keguruan dan Ilmu Pendidikan'   => 'FKIP',
            'Ilmu Komputer'                  => 'FIKOM',
            'Ekonomi Bisnis'                 => 'FEB',
        ];

        foreach ($fakultas as $i => $nama) {
            Fakultas::query()->updateOrCreate(
                ['tandha' => Str::slug($nama)],
                [
                    'jeneng'    => $nama,
                    'kodhe'     => $kodhe[$nama] ?? null,
                    'katrangan' => null,
                    'urutan'    => $i + 1,
                    'urip'      => true,
                ]
            );
        }
    }
}
