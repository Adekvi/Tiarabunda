<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Layanan\KarierService;

class KarierPageContent implements PageContentInterface
{
    public function __construct(private KarierService $karierService) {}

    public function getData(?string $slug = null): array
    {
        $karier = $this->karierService->forPage();

        if ($karier->isEmpty()) {
            $karier = $this->demoKarier();
        }

        $highlight = $karier->first();
        $items     = $karier->slice(1)->values();

        return [
            'karierHighlight' => $highlight,
            'karierItems'     => $items,
        ];
    }

    private function demoKarier()
    {
        return collect([
            (object) [
                'judhul' => 'Bursa Kerja Khusus (BKK)',
                'isi'    => '<p>Pusat Karier menyediakan informasi lowongan kerja terbaru dari perusahaan mitra yang telah bekerja sama dengan Universitas Tiara Bunda.</p>',
                'data_tambahan' => ['ikon' => 'briefcase', 'warna' => 'blue'],
            ],
            (object) [
                'judhul' => 'Pelatihan Soft Skill & Hard Skill',
                'isi'    => '<ul><li>Pelatihan penulisan CV dan Cover Letter profesional</li><li>Simulasi wawancara kerja (Mock Interview)</li><li>Pelatihan komunikasi dan etika kerja</li><li>Workshop LinkedIn dan Personal Branding</li></ul>',
                'data_tambahan' => ['ikon' => 'tool', 'warna' => 'orange'],
            ],
            (object) [
                'judhul' => 'Tracer Study & Penyaluran Lulusan',
                'isi'    => '<p>Kami memantau lulusan untuk memastikan mereka terserap di dunia industri dan memberikan umpan balik untuk perbaikan kurikulum.</p>',
                'data_tambahan' => ['ikon' => 'trending-up', 'warna' => 'green'],
            ],
            (object) [
                'judhul' => 'Kerja Sama Industri',
                'isi'    => '<p>Menjalin kemitraan strategis dengan berbagai perusahaan untuk program magang (internship) dan rekrutmen langsung di kampus.</p>',
                'data_tambahan' => ['ikon' => 'handshake', 'warna' => 'purple'],
            ],
        ]);
    }
}
