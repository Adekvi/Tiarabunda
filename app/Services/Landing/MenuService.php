<?php

namespace App\Services\Landing;

use App\Models\Landing\Menu;
use App\Models\Landing\Submenu;
use App\Models\Pendidikan\Program\Fakultas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    private const CACHE_KEY = 'landing.nav.v2';
    private const CACHE_TTL = 600;

    /**
     * Navbar: 1 query menu + 1 query submenu (eager), lalu filter di memory.
     *
     * @return Collection<int, Menu>
     */
    public function navigation(): Collection
    {
        // 1. Simpan sebagai Array murni
        $menusArray = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
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
                ->map(fn(Menu $m) => [
                    'id'     => $m->id,
                    'judhul' => $m->judhul,
                    'tandha' => $m->tandha,
                    'urutan' => $m->urutan,
                    'urip'   => $m->urip,
                    'activeSubmenus' => $m->activeSubmenus->map(fn($s) => [
                        'id'         => $s->id,
                        'menu_id'    => $s->menu_id,
                        'judhul'     => $s->judhul,
                        'tandha'     => $s->tandha,
                        'sub_judhul' => $s->sub_judhul,
                        'urutan'     => $s->urutan,
                        'urip'       => $s->urip,
                    ])->toArray(),
                ])
                ->values()
                ->toArray();
        });

        return collect($menusArray)->map(function ($m) {
            $menuObj = (object) $m;
            $menuObj->activeSubmenus = collect($m['activeSubmenus'])->map(fn($s) => (object) $s);
            return $menuObj;
        });
    }

    public function getNavFakultas(): Collection
    {
        return Cache::remember('landing.nav.fakultas.v2', self::CACHE_TTL, function () {
            return Fakultas::query()
                ->where('urip', true)
                ->whereNull('deleted_at')
                ->with(['prodis' => function ($query) {
                    $query->where('urip', true)
                        ->whereNull('deleted_at')
                        ->orderBy('urutan', 'asc');
                }])
                ->orderBy('urutan', 'asc')
                ->get();
        });
    }

    public function findPage(string $menuSlug, string $subSlug): ?Submenu
    {
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
        Cache::forget('landing.nav.fakultas.v2');
    }
}
