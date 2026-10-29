<x-landing.layout.app :title="'Pencarian — ' . config('landing.site.nama')" description="Hasil pencarian di situs kampus.">
    <x-landing.layout.topbar />
    <x-landing.layout.navbar />
    <main id="main">
        <section class="phero">
            <div class="wrap">
                <nav class="crumb" aria-label="Breadcrumb"><a href="/">Beranda</a> / Pencarian</nav>
                <p class="kick">Pencarian</p>
                <h1>{{ $q !== '' ? 'Hasil untuk “' . $q . '”' : 'Pencarian' }}</h1>
                <p class="lead">
                    {{ $q !== '' ? count($results) . ' halaman ditemukan.' : 'Ketik minimal 2 huruf untuk mencari.' }}
                </p>
            </div>
        </section>
        <section class="sec">
            <div class="wrap single">
                @forelse ($results as $r)
                    <a class="res" href="{{ $r['url'] }}"><small>{{ $r['menu'] }}</small>
                        <h3>{{ $r['t'] }}</h3>
                        <p>{{ $r['sub'] }}</p>
                    </a>
                @empty
                    @if ($q !== '')
                        <p class="lead">Tidak ada halaman yang cocok. Coba kata kunci lain, misalnya “beasiswa” atau
                            “PMB”.</p>
                    @endif
                @endforelse
            </div>
        </section>
    </main>
    <x-landing.layout.footer />
</x-landing.layout.app>
