<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing');
    }

    public function menu(string $menu)
    {
        $subs = config("landing.menus.$menu.subs");
        abort_unless($subs, 404);

        return redirect()->route('landing.page', [$menu, array_key_first($subs)]);
    }

    public function page(string $menu, string $sub)
    {
        $page = config("landing.menus.$menu.subs.$sub");
        abort_unless($page, 404);

        return view('landing.page', [
            'menu' => config("landing.menus.$menu.t"),
            'page' => $page,
        ]);
    }

    // Pengambilan data agar tidak memberatkan halaman landing
    // Controller/komponen: cache dengan stale-while-revalidate, query ringan
    // $berita = Cache::flexible('landing:berita:latest', [300, 900], fn () =>
    //     Berita::query()
    //         ->select('id', 'judul', 'slug', 'jenis', 'tanggal')   // jangan ambil kolom isi/longText
    //         ->where('terbit', true)
    //         ->latest('tanggal')
    //         ->limit(4)
    //         ->get()
    // );

    public function search(Request $request)
    {
        $q = trim(mb_substr((string) $request->query('q', ''), 0, 60));
        $results = [];

        if (mb_strlen($q) >= 2) {
            $needle = mb_strtolower($q);

            foreach (config('landing.menus') as $ms => $m) {
                foreach ($m['subs'] as $ss => $s) {
                    $hay = mb_strtolower(implode(' ', [$s['t'], $s['sub'], $this->textOf($s['blocks'])]));

                    if (str_contains($hay, $needle)) {
                        $results[] = [
                            'menu' => $m['t'],
                            't' => $s['t'],
                            'sub' => $s['sub'],
                            'url' => "/$ms/$ss",
                            'rank' => str_contains(mb_strtolower($s['t']), $needle) ? 0 : 1,
                        ];
                    }
                }
            }
            usort($results, fn($a, $b) => $a['rank'] <=> $b['rank']);
        }

        return view('landing.search', compact('q', 'results'));
    }

    private function textOf(array $blocks): string
    {
        return collect($blocks)->flatMap(fn($b) => [
            $b['t'] ?? '',
            ...($b['isi'] ?? []),
            ...($b['daftar'] ?? []),
            ...($b['type'] === 'prodi' ? array_column(config('landing.home.prodi'), 0) : []),
            ...($b['type'] === 'berita' ? array_column(config('landing.home.berita'), 't') : []),
        ])->implode(' ');
    }
}
