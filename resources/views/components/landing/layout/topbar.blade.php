@props(['site' => config('landing.site')])
<div class="top">
    <div class="wrap"><span>{{ $site['alamat'] }}</span>
        <nav aria-label="Portal">
            @foreach ($site['portal'] as $p)
                <a href="{{ $p[1] }}">{{ $p[0] }}</a>
            @endforeach
        </nav>
    </div>
</div>
