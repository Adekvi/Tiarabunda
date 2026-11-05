<?php

namespace App\Observers\Landing\Pendidikan\Program;

use App\Models\Pendidikan\Program\Fakultas;
use App\Services\Landing\LandingHomeService;

class FakultasObserver
{
    /**
     * Dipanggil untuk created & updated.
     * Termasuk toggle urip, karena toggleUrip() memakai save().
     */
    public function saved(Fakultas $fakultas): void
    {
        $this->invalidate();
    }

    /**
     * Soft delete & forceDelete sama-sama memicu event `deleted`.
     * Event forceDeleted terpisah — daftarkan juga untuk berjaga-jaga
     * kalau suatu saat pakai forceDelete() langsung di model.
     */
    public function deleted(Fakultas $fakultas): void
    {
        $this->invalidate();
    }

    public function forceDeleted(Fakultas $fakultas): void
    {
        $this->invalidate();
    }

    /**
     * Restore dari soft delete.
     */
    public function restored(Fakultas $fakultas): void
    {
        $this->invalidate();
    }

    private function invalidate(): void
    {
        app(LandingHomeService::class)->forgetFakultasProdi();
    }
}
