<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Layanan\KemahaService;
use App\Services\Landing\Pendidikan\BeasiswaService;

class BeasiswaPageContent implements PageContentInterface
{
    public function __construct(
        private readonly BeasiswaService $beasiswaService,
        private readonly KemahaService $kemahaService
    ) {}

    public function getData(?string $slug = null): array
    {
        $beasiswa = $this->beasiswaService->forPage();

        $beasiswaInfo = $this->kemahaService->forPage()->firstWhere('is_beasiswa', true);

        $selectedBeasiswa = null;
        if (filled($slug)) {
            $selectedBeasiswa = $beasiswa->firstWhere('tandha', $slug)
                ?? $this->beasiswaService->findBySlug($slug);

            abort_if($selectedBeasiswa === null, 404, 'Program beasiswa tidak ditemukan.');
        }

        return [
            'beasiswaLoaded'   => true,
            'beasiswaList'     => $beasiswa,
            'beasiswaInfo'     => $beasiswaInfo,
            'selectedBeasiswa' => $selectedBeasiswa,
        ];
    }
}
