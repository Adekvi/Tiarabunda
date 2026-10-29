@props(['menu', 'page'])
<section class="phero">
    <div class="wrap">
        <nav class="crumb" aria-label="Breadcrumb"><a href="/">Beranda</a> / {{ $menu }} /
            {{ $page['t'] }}</nav>
        <p class="kick">{{ $menu }}</p>
        <h1>{{ $page['t'] }}</h1>
        <p class="lead">{{ $page['sub'] }}</p>
    </div>
</section>
