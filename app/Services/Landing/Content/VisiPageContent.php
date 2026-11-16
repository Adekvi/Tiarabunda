<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Profil\VisiService;

class VisiPageContent implements PageContentInterface
{
    public function __construct(private VisiService $visimisiService) {}

    public function getData(?string $prodiSlug = null): array
    {
        $visimisi = $this->visimisiService->forPage();

        if ($visimisi['visi']->isEmpty() && $visimisi['misi']->isEmpty()) {
            $visimisi = [
                'visi' => collect([
                    (object) [
                        'bagean' => 'visi',
                        'judhul' => 'Visi',
                        'isi'    => 'Menjadi perguruan tinggi vokasi unggul yang menghasilkan lulusan kompeten dan inovatif.',
                        'dhaptar' => null,
                    ],
                ]),
                'misi' => collect([
                    (object) [
                        'bagean' => 'misi',
                        'judhul' => 'Misi',
                        'isi'    => null,
                        'dhaptar' => [
                            'Menyelenggarakan pendidikan yang berorientasi praktik.',
                            'Mengembangkan penelitian terapan.',
                        ],
                    ],
                ]),
                'lain' => collect([]),
            ];
        }

        return ['visimisi' => $visimisi];
    }
}
