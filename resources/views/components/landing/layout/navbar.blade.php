@props(['site' => config('landing.site'), 'menus' => config('landing.menus')])

<header class="nav" id="nav">
    <div class="wrap nav-in">
        <a class="brand" href="/">
            <img src="{{ asset('landing/assets/img/logo.webp') }}" alt="{{ $site['nama'] }}" width="46"
                height="46" />
            <span>
                <b>{{ $site['nama'] }}</b>
                <small>{{ $site['kota'] }}</small>
            </span>
        </a>

        <div class="nav-backdrop" id="nav-backdrop" aria-hidden="true"></div>

        <nav class="menu" id="menu" aria-label="Menu utama">
            <div class="menu-head">
                <a class="menu-brand" href="/">
                    <img src="{{ asset('landing/assets/img/logo.webp') }}" alt="" width="38"
                        height="38" />
                    <span>
                        <b>{{ $site['nama'] }}</b>
                        <small>{{ $site['kota'] }}</small>
                    </span>
                </a>
                <button class="menu-close" id="menu-close" type="button" aria-label="Tutup menu">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                        stroke-width="2.2" stroke-linecap="round">
                        <path d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>
            </div>

            <div class="menu-body" id="menu-body">
                @foreach ($menus as $slug => $m)
                    {{-- Menu Tautan Internal (Contoh: Beranda) --}}
                    @if (!empty($m['href']) || ($slug === 'beranda' && empty($m['subs']) && empty($m['link'])))
                        @php
                            $internalHref = $m['href'] ?? '/';
                            $trimmedPath = trim(parse_url($internalHref, PHP_URL_PATH) ?? '', '/');
                            $isInternalActive =
                                $trimmedPath === ''
                                    ? request()->is('/')
                                    : request()->is($trimmedPath) || request()->is("$trimmedPath/*");
                        @endphp
                        <a href="{{ $internalHref }}" @class(['nav-link', 'active' => $isInternalActive])>
                            <span>{{ $m['t'] }}</span>
                        </a>
                        @continue
                    @endif

                    {{-- Menu Tautan Eksternal (Contoh: Pendaftaran) --}}
                    @if (!empty($m['link']))
                        <a href="{{ $m['link'] }}" @class([
                            'nav-link',
                            'active' => request()->is(
                                ltrim(parse_url($m['link'], PHP_URL_PATH) ?? '', '/')),
                        ]) target="_blank"
                            rel="noopener noreferrer">
                            <span>{{ $m['t'] }}</span>
                            <svg class="nav-ext" viewBox="0 0 24 24" width="14" height="14" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                                <path d="M7 17L17 7M9 7h8v8" />
                            </svg>
                        </a>
                        @continue
                    @endif

                    @continue(!isset($m['subs']) || !is_array($m['subs']))

                    {{-- Menu dengan Dropdown --}}
                    <div @class(['dd', 'active' => request()->is("$slug/*")])>
                        <button type="button" class="dd-toggle" aria-expanded="false">
                            <span>{{ $m['t'] }}</span>
                            <i class="dd-caret" aria-hidden="true"></i>
                        </button>

                        <div class="dd-p">
                            @foreach ($m['subs'] as $s => $d)
                                @continue(!is_array($d) || !isset($d['t']))

                                <a href="/{{ $slug }}/{{ $s }}" @class(['dd-item', 'active' => request()->is("$slug/$s")])>
                                    {{ $d['t'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="menu-foot">
                @if (!empty($site['portal']))
                    <div class="menu-portal">
                        <span class="menu-portal-lbl">Akses Portal Cepat</span>
                        <div class="menu-portal-grid">
                            @foreach ($site['portal'] as $p)
                                <a href="{{ $p[1] }}">{{ $p[0] }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (!empty($site['hotline']))
                    <a class="menu-hotline" href="tel:{{ preg_replace('/[^0-9+]/', '', $site['hotline']) }}">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                        <span>Hotline: <strong>{{ $site['hotline'] }}</strong></span>
                    </a>
                @endif
            </div>
        </nav>

        <div class="nav-actions">
            <form @class(['srch', 'open' => request()->routeIs('landing.search')]) id="srch" action="/cari" method="get" role="search">
                <input id="q" name="q" type="search" enterkeyhint="search" placeholder="Cari di situs…"
                    aria-label="Cari" autocomplete="off" minlength="2" maxlength="60" required
                    value="{{ request('q') }}">
                <button class="srch-b" id="srch-b" type="button" aria-label="Buka pencarian" aria-expanded="false"
                    aria-controls="q">
                    <svg class="i-s" viewBox="0 0 24 24" width="19" height="19" fill="none"
                        stroke="currentColor" stroke-width="2.1" stroke-linecap="round">
                        <circle cx="11" cy="11" r="7" />
                        <path d="M20 20l-3.5-3.5" />
                    </svg>
                    <svg class="i-x" viewBox="0 0 24 24" width="19" height="19" fill="none"
                        stroke="currentColor" stroke-width="2.1" stroke-linecap="round">
                        <path d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>
            </form>

            <button class="burger" id="burger" type="button" aria-label="Buka menu" aria-expanded="false"
                aria-controls="menu">
                <i class="burger-bar" aria-hidden="true"></i>
                <i class="burger-bar" aria-hidden="true"></i>
                <i class="burger-bar" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</header>
