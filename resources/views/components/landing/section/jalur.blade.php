@props(['items' => []])

@if (count($items) > 0)
    <section class="sec alt" id="jalur">
        <div class="wrap">
            <x-landing.ui.head kick="Jalur & beasiswa" title="Pilih jalur masuk yang sesuai" />
            <div class="cards rv">
                @foreach ($items as $j)
                    <article class="card">
                        <h3>{{ $j['nama'] }}</h3>

                        <p>{{ Str::limit(strip_tags($j['deskripsi']), 120) }}</p>

                        {{-- Arahkan langsung ke detail jalur menggunakan slug (tandha) --}}
                        <a class="more" href="/pendidikan/penerimaan-mahasiswa-baru/{{ $j['tandha'] ?? '' }}">
                            Lihat detail <x-landing.ui.arrow />
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
