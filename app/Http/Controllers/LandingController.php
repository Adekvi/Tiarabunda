<?php

namespace App\Http\Controllers;

use App\Models\Landing\Submenu;
use App\Services\Landing\LandingHomeService;
use App\Services\Landing\MenuService;
use App\Services\Landing\PageContentFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(LandingHomeService $home): View
    {
        return view('landing', [
            'home' => $home->forHome(),
        ]);
    }

    public function menu(string $menu)
    {
        $nav = app(MenuService::class)->navigation();
        $found = $nav->first(fn($m) => $m->tandha === $menu);

        if ($found && $found->activeSubmenus->isNotEmpty()) {
            return redirect()->route('landing.page', [$menu, $found->activeSubmenus->first()->tandha]);
        }

        $subs = config("landing.menus.$menu.subs");
        abort_unless(is_array($subs) && $subs !== [], 404);

        return redirect()->route('landing.page', [$menu, array_key_first($subs)]);
    }

    public function page(Request $request, string $menu, string $sub, ?string $prodi = null): View
    {
        $prodiSlug = $prodi;
        $pageModel = app(MenuService::class)->findPage($menu, $sub);

        if ($pageModel !== null) {
            $siblings = Cache::remember(
                "landing.page.siblings.{$pageModel->menu_id}",
                600,
                fn() => Submenu::query()
                    ->active()
                    ->where('menu_id', $pageModel->menu_id)
                    ->ordered()
                    ->select(['id', 'menu_id', 'judhul', 'tandha', 'sub_judhul', 'urutan', 'urip'])
                    ->get()
            );

            $pageData = [
                'menu'      => $pageModel->menu->title,
                'menuSlug'  => $pageModel->menu->tandha,
                'subSlug'   => $pageModel->tandha,
                'siblings'  => $siblings,
                'page'      => [
                    't'      => $pageModel->title,
                    'sub'    => $pageModel->subtitle,
                    'blocks' => $pageModel->blocks,
                ],
            ];
        } else {
            // Fallback config
            $cfg = config("landing.menus.$menu.subs.$sub");
            abort_unless(is_array($cfg), 404);

            $siblings = collect(config("landing.menus.$menu.subs", []))
                ->map(fn(array $s, string $slug) => (object) [
                    'judhul'     => $s['t'] ?? $slug,
                    'tandha'     => $slug,
                    'sub_judhul' => $s['sub'] ?? '',
                ])
                ->values();

            $pageData = [
                'menu'     => config("landing.menus.$menu.t", ucfirst($menu)),
                'menuSlug' => $menu,
                'subSlug'  => $sub,
                'siblings' => $siblings,
                'page'     => [
                    't'      => $cfg['t'] ?? $sub,
                    'sub'    => $cfg['sub'] ?? '',
                    'blocks' => $cfg['blocks'] ?? [],
                ],
            ];
        }

        $contentData = PageContentFactory::make($sub)?->getData($prodiSlug) ?? [];

        return view('landing.page', array_merge($pageData, $contentData));
    }

    public function search(Request $request): View
    {
        $q = trim(mb_substr((string) $request->query('q', ''), 0, 60));
        $results = [];

        if (mb_strlen($q) >= 2) {
            $needle = mb_strtolower($q);

            $rows = Submenu::query()
                ->active()
                ->select(['id', 'menu_id', 'judhul', 'tandha', 'sub_judhul', 'blok_isi'])
                ->with(['menu:id,judhul,tandha,urip'])
                ->whereHas('menu', fn($q) => $q->active())
                ->get();

            if ($rows->isNotEmpty()) {
                foreach ($rows as $row) {
                    $hay = mb_strtolower(implode(' ', [
                        $row->judhul,
                        $row->sub_judhul,
                        $this->textOf($row->blocks),
                    ]));

                    if (! str_contains($hay, $needle)) {
                        continue;
                    }

                    $results[] = [
                        'menu' => $row->menu?->title ?? '',
                        't' => $row->title,
                        'sub' => $row->subtitle,
                        'url' => '/' . $row->menu?->tandha . '/' . $row->tandha,
                        'rank' => str_contains(mb_strtolower((string) $row->judhul), $needle) ? 0 : 1,
                    ];
                }
            } else {
                foreach (config('landing.menus', []) as $ms => $m) {
                    foreach ($m['subs'] ?? [] as $ss => $s) {
                        $hay = mb_strtolower(implode(' ', [
                            $s['t'] ?? '',
                            $s['sub'] ?? '',
                            $this->textOf($s['blocks'] ?? []),
                        ]));

                        if (! str_contains($hay, $needle)) {
                            continue;
                        }

                        $results[] = [
                            'menu' => $m['t'] ?? '',
                            't' => $s['t'] ?? '',
                            'sub' => $s['sub'] ?? '',
                            'url' => "/{$ms}/{$ss}",
                            'rank' => str_contains(mb_strtolower($s['t'] ?? ''), $needle) ? 0 : 1,
                        ];
                    }
                }
            }

            usort($results, fn($a, $b) => $a['rank'] <=> $b['rank']);
            $results = array_slice($results, 0, 40);
        }

        return view('landing.search', compact('q', 'results'));
    }

    private function textOf(array $blocks): string
    {
        return collect($blocks)->flatMap(fn($b) => [
            $b['t'] ?? '',
            ...($b['isi'] ?? []),
            ...($b['daftar'] ?? []),
            ...array_column($b['items'] ?? [], 't'),
            ...array_column($b['items'] ?? [], 'd'),
        ])->implode(' ');
    }
}
