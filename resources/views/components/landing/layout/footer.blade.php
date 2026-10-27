@props(['site' => config('landing.site')])
<footer class="foot">
    <div class="wrap fg">
        <div>
            <div class="brand w">
                <img src="{{ asset('landing/assets/img/logo.webp') }}" alt="" width="48" height="48"
                    loading="lazy" />
                <span><b>{{ $site['nama'] }}</b><small>{{ $site['kota'] }}</small></span>
            </div>
            <p>{{ $site['kontak']['alamat'] }}<br>{{ $site['kontak']['telp'] }} · {{ $site['kontak']['email'] }}</p>
        </div>
        @foreach ($site['footer'] as $c)
            <div>
                <h4>{{ $c[0] }}</h4>
                @foreach ($c[1] as $l)
                    <a href="{{ $l[1] }}">{{ $l[0] }}</a>
                @endforeach
            </div>
        @endforeach
    </div>
    <div class="wrap copy">© {{ date('Y') }} {{ $site['nama'] }}. Hak cipta dilindungi.</div>
</footer>
