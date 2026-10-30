{{-- @props(['menu', 'page'])
<section class="phero">
    <div class="wrap">
        <nav class="crumb" aria-label="Breadcrumb"><a href="/">Beranda</a> / {{ $menu }} /
            {{ $page['t'] }}</nav>
        <p class="kick">{{ $menu }}</p>
        <h1>{{ $page['t'] }}</h1>
        <p class="lead">{{ $page['sub'] }}</p>
    </div>
</section> --}}

@props(['menu', 'page'])

<section class="phero">
    {{-- Background image --}}
    <div class="phero-bg" aria-hidden="true">
        <img
            src="{{ asset('landing/assets/img/pghero.webp') }}"
            alt=""
            width="1920"
            height="600"
            loading="eager"
            decoding="async"
        >
        {{-- Backdrop overlay: gelap di kiri biar teks kontras, lebih transparan ke kanan --}}
        <div class="phero-overlay"></div>
    </div>

    <div class="wrap phero-content">
        <nav class="crumb" aria-label="Breadcrumb">
            <a href="/">Beranda</a>
            <span class="crumb-sep" aria-hidden="true">/</span>
            <span>{{ $menu }}</span>
            <span class="crumb-sep" aria-hidden="true">/</span>
            <span aria-current="page">{{ $page['t'] }}</span>
        </nav>

        <p class="kick">{{ $menu }}</p>
        <h1>{{ $page['t'] }}</h1>
        <p class="lead">{{ $page['sub'] }}</p>
    </div>
</section>
