<?php

namespace App\Observers\Landing\Pendidikan\Program;

use App\Models\Pendidikan\Program\Prodi;
use App\Services\Landing\LandingHomeService;

class ProdiObserver
{
    public function saved(Prodi $prodi): void
    {
        $this->invalidate();
    }

    public function deleted(Prodi $prodi): void
    {
        $this->invalidate();
    }

    public function forceDeleted(Prodi $prodi): void
    {
        $this->invalidate();
    }

    public function restored(Prodi $prodi): void
    {
        $this->invalidate();
    }

    private function invalidate(): void
    {
        app(LandingHomeService::class)->forgetFakultasProdi();
    }
}
