@props(['site' => config('landing.site')])
<header class="nav" id="nav">
    <div class="wrap nav-in">
        <a class="brand" href="/">
            <img src="{{ asset('landing/assets/img/logo.webp') }}" alt="" width="48" height="48"
                loading="lazy" />
            <span><b>{{ $site['nama'] }}</b><small>{{ $site['kota'] }}</small></span></a>
        <nav class="menu" id="menu" aria-label="Menu utama">
            @foreach ($site['menu'] as $m)
                @if (isset($m['sub']))
                    <div class="dd"><button type="button" aria-expanded="false">{{ $m['t'] }}<i></i></button>
                        <div class="dd-p">
                            @foreach ($m['sub'] as $s)
                                <a href="{{ $s[1] }}">{{ $s[0] }}</a>
                            @endforeach
                        </div>
                </div>@else<a class="pl" href="{{ $m['href'] }}">{{ $m['t'] }}</a>
                @endif
            @endforeach
        </nav>
        {{-- <a class="btn sm" href="{{ $site['pmb'] }}">Daftar PMB</a> --}}
        <button class="burger" id="burger" type="button" aria-label="Buka menu"
            aria-expanded="false"><span></span></button>
    </div>
</header>
