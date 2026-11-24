@props(['site' => config('landing.site')])

<div class="top">
    <div class="wrap top-in">
        <div class="top-info">
            <span class="hotline">Hotline: <strong>{{ $site['hotline'] }}</strong></span>
            @if (!empty($site['alamat']))
                <span class="top-sep" aria-hidden="true">|</span>
                <span class="top-addr">{{ $site['alamat'] }}</span>
            @endif
        </div>
        <nav class="top-portal" aria-label="Portal">
            @foreach ($site['portal'] as $p)
                <a href="{{ $p[1] }}">{{ $p[0] }}</a>
            @endforeach
        </nav>
    </div>
</div>
