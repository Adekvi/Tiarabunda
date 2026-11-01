<?php

namespace App\Services\Landing;

use App\Models\Landing\Menu;
use App\Models\Landing\Submenu;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    private const CACHE_KEY = 'landing.nav';
    private const CACHE_TTL = 600; // 10 menit

    /**
     * Navbar: 1 query menu + 1 query submenu (eager), lalu filter di memory.
     *
     * @return Collection<int, Menu>
     */
    public function navigation(): Collection
    {
        /** @var Collection<int, Menu> $menus */
        $menus = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            /** @var EloquentCollection<int, Menu> $rows */
            $rows = Menu::query()
                ->active()
                ->ordered()
                ->select(['id', 'judhul', 'tandha', 'urutan', 'urip'])
                ->with([
                    'activeSubmenus:id,menu_id,judhul,tandha,sub_judhul,urutan,urip',
                ])
                ->get();

            return $rows
                ->filter(fn(Menu $m) => $m->activeSubmenus->isNotEmpty())
                ->values();
        });

        return $menus;
    }

    public function findPage(string $menuSlug, string $subSlug): ?Submenu
    {
        // 1 query + join constraint menu aktif (bukan N+1)
        return Submenu::query()
            ->active()
            ->byTandha($subSlug)
            ->select([
                'id',
                'menu_id',
                'judhul',
                'tandha',
                'sub_judhul',
                'blok_isi',
                'urutan',
                'urip',
            ])
            ->whereHas(
                'menu',
                fn($q) => $q->active()->byTandha($menuSlug)
            )
            ->with([
                'menu:id,judhul,tandha,urip',
            ])
            ->first();
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
