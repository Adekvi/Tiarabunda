@props(['hero'])
<section class="phero">
    <div class="wrap">
        <nav class="crumb" aria-label="Breadcrumb"><a href="/">Beranda</a> / {{ $hero['kick'] }}</nav>
        <p class="kick">{{ $hero['kick'] }}</p>
        <h1>{{ $hero['judul'] }}</h1>
        <p class="lead">{{ $hero['sub'] }}</p>
    </div>
</section>
