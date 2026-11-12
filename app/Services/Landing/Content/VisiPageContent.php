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

        // Fallback jika kosong
        if (empty($visimisi['visi']) && empty($visimisi['misi'])) {
            $visimisi = [
                'visi' => collect([
                    (object) [
                        'bagean' => 'visi',
                        'judhul' => 'Visi',
                        'isi' => 'Menjadi perguruan tinggi vokasi unggul yang menghasilkan lulusan kompeten dan inovatif dalam menjawab kebutuhan masyarakat serta dunia kerja di Jawa Barat.',
                        'dhaptar' => null,
                    ],
                ]),
                'misi' => collect([
                    (object) [
                        'bagean' => 'misi',
                        'judhul' => 'Misi',
                        'isi' => null,
                        'dhaptar' => [
                            'Menyelenggarakan pendidikan dan pengajaran yang berorientasi praktik serta relevan dengan kebutuhan industri.',
                            'Mengembangkan penelitian terapan yang bermanfaat bagi masyarakat.',
                            'Melaksanakan pengabdian kepada masyarakat berbasis hasil pembelajaran dan penelitian.',
                            'Membangun tata kelola kampus yang transparan, akuntabel, dan berorientasi mutu.',
                            'Memperkuat kerja sama dengan pemerintah, perguruan tinggi, dan mitra industri.',
                        ],
                    ],
                ]),
                'lain' => collect([
                    (object) [
                        'bagean' => 'nilai',
                        'judhul' => 'Nilai yang kami jaga',
                        'isi' => 'Nilai ini menjadi rujukan dalam keputusan akademik dan layanan mahasiswa.',
                        'dhaptar' => [
                            'Kompetensi — keterampilan yang terukur dan siap kerja.',
                            'Integritas — proses belajar dan layanan yang dapat dipertanggungjawabkan.',
                            'Kolaborasi — kemitraan sebagai bagian dari ekosistem belajar.',
                            'Kebermanfaatan — dampak nyata bagi masyarakat dan lulusan.',
                        ],
                    ],
                ]),
            ];
        }

        return ['visimisi' => $visimisi];
    }
}
