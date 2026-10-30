<?php

use App\Models\Landing\Menu;
use App\Models\Landing\Submenu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    public function navigation(): Collection
    {
        return Cache::remember('landing.nav', 300, function () {
            return Menu::active()
                ->with(['activeSubmenus'])
                ->orderBy('sort_order')
                ->get()
                // filter parent yang tidak punya child aktif
                ->filter(fn($m) => $m->activeSubmenus->isNotEmpty())
                ->values();
        });
    }

    public function findPage(string $menuSlug, string $subSlug): ?Submenu
    {
        return Submenu::active()
            ->where('slug', $subSlug)
            ->whereHas('menu', fn($q) => $q->active()->where('slug', $menuSlug))
            ->with('menu')
            ->first();
    }

    public function clearCache(): void
    {
        Cache::forget('landing.nav');
    }
}
