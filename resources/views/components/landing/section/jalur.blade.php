@props(['items' => config('landing.home.jalur')])
<section class="sec alt" id="jalur">
    <div class="wrap">
        <x-landing.ui.head kick="Jalur & beasiswa" title="Pilih jalur masuk yang sesuai" />
        <div class="cards rv">
            @foreach ($items as $j)
                <article class="card">
                    <h3>{{ $j[0] }}</h3>
                    <p>{{ $j[1] }}</p><a class="more" href="/pmb">Lihat detail <x-landing.ui.arrow /></a>
                </article>
            @endforeach
        </div>
    </div>
</section>
