<?php

namespace App\Observers\Landing\Informasi\Berita;

use App\Models\Informasi\Berita;
use App\Services\Landing\LandingHomeService;

class BeritaObserver
{
    public function __construct(
        private readonly LandingHomeService $home,
    ) {}

    public function saved(Berita $berita): void
    {
        $this->home->forgetBerita();
    }

    public function deleted(Berita $berita): void
    {
        $this->home->forgetBerita();
    }

    public function restored(Berita $berita): void
    {
        $this->home->forgetBerita();
    }

    public function forceDeleted(Berita $berita): void
    {
        $this->home->forgetBerita();
    }
}
