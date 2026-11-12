<?php

namespace App\Services\Landing;

use App\Services\Landing\Content\AkreditasiPageContent;
use App\Services\Landing\Content\BeasiswaPageContent;
use App\Services\Landing\Content\PmbPageContent;
use App\Services\Landing\Contracts\PageContentInterface;
use App\Services\Landing\Content\ProgramPageContent;
use App\Services\Landing\Content\SejarahPageContent;
use App\Services\Landing\Content\VisiPageContent;

class PageContentFactory
{
    /**
     * Menentukan class Service mana yang harus dipanggil berdasarkan slug submenu.
     */
    public static function make(string $subSlug): ?PageContentInterface
    {
        return match ($subSlug) {
            'sejarah' => app(SejarahPageContent::class),
            'visi-misi' => app(VisiPageContent::class),
            'program-pendidikan' => app(ProgramPageContent::class),
            'penerimaan-mahasiswa-baru'  => app(PmbPageContent::class),
            'beasiswa'                   => app(BeasiswaPageContent::class),
            'akreditasi'                 => app(AkreditasiPageContent::class),
            default => null,
        };
    }
}
