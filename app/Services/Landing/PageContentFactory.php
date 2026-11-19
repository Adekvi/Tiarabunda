<?php

namespace App\Services\Landing;

use App\Services\Landing\Content\AkreditasiPageContent;
use App\Services\Landing\Content\BeasiswaPageContent;
use App\Services\Landing\Content\BeritaPageContent;
use App\Services\Landing\Content\FaqPageContent;
use App\Services\Landing\Content\FasilitasPageContent;
use App\Services\Landing\Content\KarierPageContent;
use App\Services\Landing\Content\KegiatanPageContent;
use App\Services\Landing\Content\KemahaPageContent;
use App\Services\Landing\Content\LppmPageContent;
use App\Services\Landing\Content\PerpusPageContent;
use App\Services\Landing\Content\PmbPageContent;
use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Content\ProgramPageContent;
use App\Services\Landing\Content\SambutanPageContent;
use App\Services\Landing\Content\SejarahPageContent;
use App\Services\Landing\Content\StrukturPageContent;
use App\Services\Landing\Content\VisiPageContent;

class PageContentFactory
{
    /**
     * Menentukan class Service mana yang harus dipanggil berdasarkan slug submenu.
     */
    public static function make(string $subSlug): ?PageContentInterface
    {
        return match ($subSlug) {
            'sejarah'                       => app(SejarahPageContent::class),
            'visi-misi'                     => app(VisiPageContent::class),
            'sambutan'                      => app(SambutanPageContent::class),
            'struktur'                      => app(StrukturPageContent::class),
            'program-pendidikan'            => app(ProgramPageContent::class),
            'penerimaan-mahasiswa-baru'     => app(PmbPageContent::class),
            'beasiswa'                      => app(BeasiswaPageContent::class),
            'akreditasi'                    => app(AkreditasiPageContent::class),
            'perpustakaan'                  => app(PerpusPageContent::class),
            'kemahasiswaan'                 => app(KemahaPageContent::class),
            'pusat-karier'                  => app(KarierPageContent::class),
            'fasilitas'                     => app(FasilitasPageContent::class),
            'lppm'                          => app(LppmPageContent::class),
            'berita-terbaru'                => app(BeritaPageContent::class),
            'kegiatan-kampus'               => app(KegiatanPageContent::class),
            'faq'                           => app(FaqPageContent::class),
            default => null,
        };
    }
}
