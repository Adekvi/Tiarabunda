<?php

namespace App\Services\Landing;

use App\Models\Informasi\Faq;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class LandingHomeService
{
    private const TTL = 300; // 5 menit

    /**
     * Data beranda yang sudah disiapkan untuk view.
     * Section ditambah bertahap di method ini.
     *
     * @return array{faq: Collection<int, array{0: string, 1: string}>}
     */
    public function forHome(): array
    {
        return [
            'faq' => $this->faqs(),
            // 'berita' => $this->beritas(), // tahap berikutnya
            // 'prodi'  => $this->prodis(),
        ];
    }

    /**
     * Format sama dengan config lama: [pertanyaan, jawaban]
     * agar komponen Blade tidak dirombak total.
     *
     * @return Collection<int, array{0: string, 1: string}>
     */
    public function faqs(): Collection
    {
        return Cache::remember('landing.home.faq', self::TTL, function () {
            return Faq::query()
                ->select(['id', 'pitakon', 'wangsulan', 'urutan'])
                ->active()
                ->ordered()
                ->limit(20)
                ->get()
                ->map(fn($f) => [
                    $f->pitakon,
                    $f->wangsulan,
                ])
                ->values();
        });
    }

    public function forgetFaq(): void
    {
        Cache::forget('landing.home.faq');
    }

    /** Panggil saat perlu flush semua cache beranda */
    public function forgetHome(): void
    {
        Cache::forget('landing.home.faq');
        // Cache::forget('landing.home.berita');
    }
}
