<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Layanan\FasilitasService;

class FasilitasPageContent implements PageContentInterface
{
    public function __construct(private FasilitasService $fasilitasService) {}

    public function getData(?string $slug = null): array
    {
        $fasilitas = $this->fasilitasService->forPage();

        // Fallback demo (Hanya jika benar-benar kosong di DB)
        if ($fasilitas->isEmpty()) {
            $fasilitas = $this->demoFasilitas();
        }

        return [
            'fasilitasItems' => $fasilitas,
        ];
    }

    private function demoFasilitas()
    {
        return collect([
            (object) [
                'id'         => 1,
                'jeneng'     => 'Laboratorium Komputer',
                'tandha'     => 'lab-komputer',
                'katrangan'  => '<p>Laboratorium dengan spesifikasi tinggi untuk praktik pemrograman dan desain.</p>',
                'thumb_url'  => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=600&q=80',
                'gambar_url' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=1200&q=80',
            ],
            (object) [
                'id'         => 2,
                'jeneng'     => 'Perpustakaan',
                'tandha'     => 'perpustakaan',
                'katrangan'  => '<p>Ruang baca yang nyaman dengan koleksi buku dan jurnal yang lengkap.</p>',
                'thumb_url'  => 'https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=600&q=80',
                'gambar_url' => 'https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1200&q=80',
            ],
        ]);
    }
}
