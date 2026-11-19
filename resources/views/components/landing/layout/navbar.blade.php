@props(['site' => config('landing.site'), 'menus' => config('landing.menus')])

@php
    $navFakultas = app(\App\Services\Landing\Pendidikan\ProgramService::class)->getFakultasWithProdi();

    $isOnlyProdi = fn(array $m) => isset($m['subs']) &&
        is_array($m['subs']) &&
        count($m['subs']) === 1 &&
        array_key_exists('program-pendidikan', $m['subs']);
@endphp

<header class="nav" id="nav">
    <div class="wrap nav-in">
        <a class="brand" href="/">
            <img src="{{ asset('landing/assets/img/logo.webp') }}" alt="" width="48" height="48" />
            <span><b>{{ $site['nama'] }}</b><small>{{ $site['kota'] }}</small></span>
        </a>

        <nav class="menu" id="menu" aria-label="Menu utama">
            @foreach ($menus as $slug => $m)
                @if (!empty($m['link']))
                    <a href="{{ $m['link'] }}" @class([
                        'nav-link',
                        'active' => request()->is(
                            ltrim(parse_url($m['link'], PHP_URL_PATH) ?? '', '/')),
                    ]) target="_blank">
                        {{ $m['t'] }}
                    </a>
                    @continue
                @endif

                @continue(!isset($m['subs']) || !is_array($m['subs']))

                <div @class(['dd', 'active' => request()->is("$slug/*")])>
                    <button type="button" aria-expanded="false">{{ $m['t'] }}<i></i></button>
                    <div class="dd-p">

                        @if ($isOnlyProdi($m) && $navFakultas->isNotEmpty())
                            <div class="dd-fakultas-list">
                                @foreach ($navFakultas as $fak)
                                    @php
                                        $rawName = trim($fak->jeneng);
                                        $cleanName = preg_replace('/^fakultas\s+/i', '', $rawName);
                                        $fakId = 'fak-' . ($fak->id ?? $loop->index);
                                    @endphp

                                    <div class="dd-fakultas-group" data-fak-group>
                                        <button type="button" class="dd-fak-toggle" aria-expanded="false"
                                            aria-controls="{{ $fakId }}">
                                            <span class="dd-fak-toggle-label">
                                                <span class="dd-fak-label">Fakultas</span>
                                                <span class="dd-fak-name">{{ $cleanName }}</span>
                                            </span>
                                            <i class="dd-fak-arrow"></i>
                                        </button>

                                        <div class="dd-fak-panel" id="{{ $fakId }}" hidden>
                                            <div class="dd-prodi-list">
                                                @forelse($fak->prodis as $prodi)
                                                    @php
                                                        $prodiSlug = $prodi->tandha ?? $prodi->id;
                                                        $prodiPath = "$slug/program-pendidikan/$prodiSlug";
                                                    @endphp
                                                    <a href="/{{ $prodiPath }}" @class(['dd-prodi', 'active' => request()->is($prodiPath)])>
                                                        <span class="dd-prodi-name">
                                                            @if ($prodi->undhak)
                                                                <span
                                                                    class="dd-prodi-jenjang">{{ $prodi->undhak }}</span>
                                                            @endif
                                                            {{ $prodi->jeneng }}
                                                        </span>
                                                    </a>
                                                @empty
                                                    <span class="dd-empty">Belum ada prodi</span>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            @foreach ($m['subs'] as $s => $d)
                                @continue(!is_array($d) || !isset($d['t']))

                                @if ($s === 'program-pendidikan' && $navFakultas->isNotEmpty())
                                    <div class="dd-nested">
                                        <button type="button" class="dd-nested-toggle" aria-expanded="false">
                                            <span>{{ $d['t'] }}</span>
                                            <i class="dd-arrow"></i>
                                        </button>

                                        <div class="dd-p dd-nested-panel">
                                            @foreach ($navFakultas as $fak)
                                                @php
                                                    $rawName = trim($fak->jeneng);
                                                    $cleanName = preg_replace('/^fakultas\s+/i', '', $rawName);
                                                    $fakId = 'nfak-' . ($fak->id ?? $loop->index);
                                                @endphp

                                                <div class="dd-fakultas-group" data-fak-group>
                                                    <button type="button" class="dd-fak-toggle" aria-expanded="false"
                                                        aria-controls="{{ $fakId }}">
                                                        <span class="dd-fak-toggle-label">
                                                            <span class="dd-fak-label">Fakultas</span>
                                                            <span class="dd-fak-name">{{ $cleanName }}</span>
                                                        </span>
                                                        <i class="dd-fak-arrow"></i>
                                                    </button>

                                                    <div class="dd-fak-panel" id="{{ $fakId }}" hidden>
                                                        <div class="dd-prodi-list">
                                                            @forelse($fak->prodis as $prodi)
                                                                @php
                                                                    $prodiSlug = $prodi->tandha ?? $prodi->id;
                                                                    $prodiPath = "$slug/$s/$prodiSlug";
                                                                @endphp
                                                                <a href="/{{ $prodiPath }}"
                                                                    @class(['dd-prodi', 'active' => request()->is($prodiPath)])>
                                                                    <span class="dd-prodi-name">
                                                                        @if ($prodi->undhak)
                                                                            <span
                                                                                class="dd-prodi-jenjang">{{ $prodi->undhak }}</span>
                                                                        @endif
                                                                        {{ $prodi->jeneng }}
                                                                    </span>
                                                                </a>
                                                            @empty
                                                                <span class="dd-empty">Belum ada prodi</span>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <a href="/{{ $slug }}/{{ $s }}" @class(['active' => request()->is("$slug/$s")])>
                                        {{ $d['t'] }}
                                    </a>
                                @endif
                            @endforeach
                        @endif

                    </div>
                </div>
            @endforeach
        </nav>

        <form @class(['srch', 'open' => request()->routeIs('landing.search')]) id="srch" action="/cari" method="get" role="search">
            <input id="q" name="q" type="search" enterkeyhint="search" placeholder="Cari di situs…"
                aria-label="Cari" autocomplete="off" minlength="2" maxlength="60" required
                value="{{ request('q') }}">
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
