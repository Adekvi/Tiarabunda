<?php

namespace App\Services\Landing\Content;

use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Profil\SejarahService;

class SejarahPageContent implements PageContentInterface
{
    public function __construct(private SejarahService $sejarahService) {}

    public function getData(?string $prodiSlug = null): array
    {
        $sejarah = $this->sejarahService->forPage();

        // Fallback ke demo jika kosong (bisa Anda pindahkan ke Seeder jika sudah production)
        if ($sejarah === null) {
            $sejarah = (object) [
                'judhul' => 'Dari kebutuhan lapangan, lahir institusi vokasi',
                'isi' => "Paragraf pertama.\n\nParagraf kedua.",
                'path_sampul' => null,
                'kronologi' => [
                    ['tahun' => 'Fondasi', 'judhul' => 'Peletakan dasar institusi', 'isi' => 'Program vokasi mulai ditata dengan fokus praktik.'],
                    ['tahun' => 'Hari ini', 'judhul' => 'Fokus mutu dan kesiapan lulusan', 'isi' => 'Layanan akademik dan penjaminan mutu terus diperkuat.'],
                ],
            ];
        }

        return ['sejarah' => $sejarah];
    }
}
