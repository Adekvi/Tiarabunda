<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Layanan\KemahaService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class KemahaPageContent implements PageContentInterface
{
    public function __construct(
        private readonly KemahaService $kemahasiswaanService
    ) {}

    public function getData(?string $slug = null): array
    {
        if ($slug === 'beasiswa') {
            throw new HttpResponseException(redirect('/layanan/beasiswa', 301));
        }

        if ($slug && Str::startsWith($slug, 'beasiswa/')) {
            $beasiswaSlug = Str::after($slug, 'beasiswa/');
            throw new HttpResponseException(redirect('/layanan/beasiswa/' . $beasiswaSlug, 301));
        }

        $kemahasiswaan = $this->kemahasiswaanService->forPage()
            ->reject(fn($item) => (bool) ($item->is_beasiswa ?? false))
            ->values();

        if ($kemahasiswaan->isEmpty()) {
            $kemahasiswaan = $this->demoKemahasiswaan();
        }

        $selectedKemahasiswaan = null;
        if (filled($slug)) {
            $selectedKemahasiswaan = $kemahasiswaan->firstWhere('tandha', $slug);

            abort_if($selectedKemahasiswaan === null, 404, 'Informasi kemahasiswaan tidak ditemukan.');
        }

        return [
            'kemahasiswaanHighlight' => $kemahasiswaan->first(),
            'kemahasiswaanItems'     => $kemahasiswaan->slice(1)->values(),
            'selectedKemahasiswaan'  => $selectedKemahasiswaan,
        ];
    }

    private function demoKemahasiswaan()
    {
        return collect([
            (object) [
                'judhul'        => 'Biro Kemahasiswaan & Alumni',
                'tandha'        => 'biro-kemahasiswaan-alumni',
                'is_beasiswa'   => false,
                'isi'           => '<p>Biro yang mengelola layanan kemahasiswaan, organisasi mahasiswa, pengembangan minat bakat, dan hubungan alumni.</p>',
                'data_tambahan' => ['ikon' => 'users', 'warna' => 'blue'],
            ],
        ]);
    }
}
