<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Layanan\AkademikService;

class AkademikPageContent implements PageContentInterface
{
    public function __construct(
        private AkademikService $akademikService
    ) {}

    public function getData(?string $slug = null): array
    {
        $dokumen = $this->akademikService->forPage();

        $kategoriList = $dokumen
            ->pluck('kategori')
            ->filter()
            ->unique()
            ->values();

        return [
            'akademikItems'    => $dokumen,
            'akademikKategori' => $kategoriList,
        ];
    }
}
