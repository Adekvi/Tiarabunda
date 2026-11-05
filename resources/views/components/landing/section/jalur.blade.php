@props(['items' => []])

@if (count($items) > 0)
    <section class="sec alt" id="jalur">
        <div class="wrap">
            <x-landing.ui.head kick="Jalur & beasiswa" title="Pilih jalur masuk yang sesuai" />
            <div class="cards rv">
                @foreach ($items as $j)
                    <article class="card">
                        <h3>{{ $j['nama'] }}</h3>

                        {{-- Render deskripsi sebagai teks biasa, batasi maksimal ~120 karakter --}}
                        <p>{{ Str::limit(strip_tags($j['deskripsi']), 120) }}</p>

                        <a class="more" href="{{ config('landing.site.pmb') }}">Lihat detail
                            <x-landing.ui.arrow />
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
