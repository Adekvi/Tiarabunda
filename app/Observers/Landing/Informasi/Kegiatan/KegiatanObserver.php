<?php

namespace App\Observers\Landing\Informasi\Kegiatan;

use App\Models\Informasi\Kegiatan;
// use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Cache;

class KegiatanObserver
{
    private function clearCache(): void
    {
        Cache::forget('landing.home.kegiatan');
    }

    public function created(Kegiatan $kegiatan): void
    {
        $this->clearCache();
    }

    public function updated(Kegiatan $kegiatan): void
    {
        $this->clearCache();
    }

    public function deleted(Kegiatan $kegiatan): void
    {
        $this->clearCache();
    }

    public function restored(Kegiatan $kegiatan): void
    {
        $this->clearCache();
    }

    public function forceDeleted(Kegiatan $kegiatan): void
    {
        $this->clearCache();
    }
}
