@props(['site' => config('landing.site'), 'menus' => config('landing.menus')])
<header class="nav" id="nav">
    <div class="wrap nav-in">
        <a class="brand" href="/">
            <img src="{{ asset('landing/assets/img/logo.webp') }}" alt="" width="48" height="48" />
            <span><b>{{ $site['nama'] }}</b><small>{{ $site['kota'] }}</small></span></a>
        <nav class="menu" id="menu" aria-label="Menu utama">
            @foreach ($menus as $slug => $m)
                <div @class(['dd', 'active' => request()->is("$slug/*")])>
                    <button type="button" aria-expanded="false">{{ $m['t'] }}<i></i></button>
                    <div class="dd-p">
                        @foreach ($m['subs'] as $s => $d)
                            <a href="/{{ $slug }}/{{ $s }}"
                                @class(['active' => request()->is("$slug/$s")])>{{ $d['t'] }}</a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>
        <form @class(['srch', 'open' => request()->routeIs('landing.search')]) id="srch" action="/cari" method="get" role="search">
            <input id="q" name="q" type="search" enterkeyhint="search" placeholder="Cari di situs…"
                aria-label="Cari" autocomplete="off" minlength="2" maxlength="60" required value="{{ request('q') }}">
            <button class="srch-b" id="srch-b" type="button" aria-label="Buka pencarian" aria-expanded="false"
                aria-controls="q">
                <svg class="i-s" viewBox="0 0 24 24" width="20" height="20" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <circle cx="11" cy="11" r="7" />
                    <path d="M20 20l-3.5-3.5" />
                </svg>
                <svg class="i-x" viewBox="0 0 24 24" width="20" height="20" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </form>

        <button class="burger" id="burger" type="button" aria-label="Buka menu"
            aria-expanded="false"><span></span></button>
    </div>
</header>
