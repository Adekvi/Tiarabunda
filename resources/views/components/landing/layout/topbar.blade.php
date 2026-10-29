@props(['site' => config('landing.site')])
<div class="top">
    <div class="wrap">
        <div class="justify-content-start">
            <span class="hotline">Hotline:
                {{ $site['hotline'] }}
            </span>
            <span>|</span>
            <span>{{ $site['alamat'] }}</span>
        </div>
        <nav aria-label="Portal">
            @foreach ($site['portal'] as $p)
                <a href="{{ $p[1] }}">{{ $p[0] }}</a>
            @endforeach
        </nav>
    </div>
</div>
