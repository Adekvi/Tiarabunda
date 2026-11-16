<?php

namespace App\Services\Landing\Informasi;

use App\Models\Informasi\Faq;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FaqService
{
    private const CACHE_TTL = 600;
    private const CACHE_KEY = 'landing.faq.forPage.v1';

    public function forPage(): Collection
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Faq::query()
                ->active()
                ->ordered()
                ->get()
                ->map(fn($f) => [
                    'id'        => $f->id,
                    'pitakon'   => $f->pitakon,
                    'wangsulan' => $f->wangsulan,
                    'urutan'    => $f->urutan,
                ])
                ->all();
        });

        return collect($rows)->map(fn(array $r) => (object) $r);
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
