<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Pendidikan\ProgramService;

class ProgramPageContent implements PageContentInterface
{
    public function __construct(private ProgramService $programService) {}

    public function getData(?string $prodiSlug = null): array
    {
        $program = $this->programService->forPage();

        // Fallback demo jika kosong
        if ($program->isEmpty()) {
            $program = $this->demoProgram();
        }

        $selectedProdi = null;

        // Cari detail prodi berdasarkan slug URL
        if ($prodiSlug) {
            foreach ($program as $fak) {
                $found = collect(data_get($fak, 'activeProdis', data_get($fak, 'prodis', [])))
                    ->firstWhere('tandha', $prodiSlug);

                if ($found) {
                    // Clone agar tidak mengubah original collection
                    $selectedProdi = clone $found;
                    $selectedProdi->fakultas_jeneng = data_get($fak, 'jeneng');
                    $selectedProdi->fakultas_kodhe = data_get($fak, 'kodhe');
                    break;
                }
            }
            abort_if($selectedProdi === null, 404, 'Program studi tidak ditemukan.');
        }

        return [
            'program'       => $program,
            'selectedProdi' => $selectedProdi,
        ];
    }

    private function demoProgram()
    {
        return collect([
            (object) [
                'jeneng'     => 'Fakultas Ilmu Kesehatan',
                'tandha'     => 'fik',
                'kodhe'      => 'FIK',
                'katrangan'  => 'Program vokasi bidang kesehatan dengan penekanan praktik klinik dan laboratorium.',
                'activeProdis' => collect([
                    (object) [
                        'jeneng'      => 'Kebidanan',
                        'undhak'      => 'D3',
                        'tandha'      => 'd3-kebidanan',
                        'katrangan'   => 'Menyiapkan tenaga kebidanan yang siap layanan primer.',
                        'akreditasi'  => (object) ['status_akred' => 'Baik Sekali', 'jeneng' => 'Akreditasi Kebidanan D3'],
                    ],
                    (object) [
                        'jeneng'      => 'Farmasi',
                        'undhak'      => 'D3',
                        'tandha'      => 'd3-farmasi',
                        'katrangan'   => 'Fokus pada pelayanan kefarmasian dan praktik laboratorium.',
                        'akreditasi'  => (object) ['status_akred' => 'Baik', 'jeneng' => 'Akreditasi Farmasi D3'],
                    ],
                ]),
            ],
            (object) [
                'jeneng'     => 'Fakultas Ilmu Komputer',
                'tandha'     => 'fikom',
                'kodhe'      => 'FIKOM',
                'katrangan'  => 'Program berbasis proyek dan kebutuhan industri digital.',
                'activeProdis' => collect([
                    (object) [
                        'jeneng'      => 'Teknik Informatika',
                        'undhak'      => 'D3',
                        'tandha'      => 'd3-teknik-informatika',
                        'katrangan'   => 'Pengembangan perangkat lunak dan sistem informasi.',
                        'akreditasi'  => null,
                    ],
                ]),
            ],
        ]);
    }
}
