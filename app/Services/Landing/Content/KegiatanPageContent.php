<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Informasi\KegiatanService;

class KegiatanPageContent implements PageContentInterface
{
    public function __construct(private KegiatanService $kegiatanService) {}

    public function getData(?string $slug = null): array
    {
        $kegiatan = $this->kegiatanService->forPage();

        if ($kegiatan->isEmpty()) {
            $kegiatan = $this->demoKegiatan();
        }

        $selectedKegiatan = null;

        if ($slug) {
            $selectedKegiatan = $kegiatan->firstWhere('tandha', $slug);
            abort_if($selectedKegiatan === null, 404, 'Kegiatan tidak ditemukan.');
        }

        return [
            'kegiatanList'     => $kegiatan,
            'selectedKegiatan' => $selectedKegiatan,
        ];
    }

    private function demoKegiatan()
    {
        return collect([
            (object) [
                'id'            => 1,
                'judhul'        => 'Seminar Nasional Kesehatan Vokasi',
                'tandha'        => 'seminar-nasional',
                'katrangan'     => '<p>Seminar tahunan yang membahas perkembangan terbaru di bidang kesehatan vokasi bersama praktisi ahli.</p>',
                'papan'         => 'Auditorium Utama',
                'wektu_mulai'   => now()->addDays(5)->toIso8601String(),
                'wektu_rampung' => now()->addDays(5)->addHours(4)->toIso8601String(),
                'sampul_url'    => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&q=80',
                'thumb_url'     => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80',
            ],
            (object) [
                'id'            => 2,
                'judhul'        => 'Pelatihan Soft Skill Mahasiswa Baru',
                'tandha'        => 'pelatihan-soft-skill',
                'katrangan'     => '<p>Pelatihan untuk membekali mahasiswa baru dengan keterampilan komunikasi, kerja sama tim, dan etika profesional.</p>',
                'papan'         => 'Gedung A, Ruang 201',
                'wektu_mulai'   => now()->addDays(10)->toIso8601String(),
                'wektu_rampung' => now()->addDays(11)->toIso8601String(),
                'sampul_url'    => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=1200&q=80',
                'thumb_url'     => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=600&q=80',
            ],
        ]);
    }
}
