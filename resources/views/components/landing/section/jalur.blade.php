@props(['items' => []])

@if (count($items) > 0)
    <section class="sec jalur-sec bg-split" id="jalur" aria-labelledby="jalur-title">
        <div class="wrap">
            <x-landing.ui.head kick="Jalur & beasiswa" title="Pilih jalur masuk yang sesuai" id="jalur-title" />

            <div class="jalur-cards rv">
                @foreach ($items as $j)
                    <article class="jalur-card">
                        <div class="jalur-card__icon" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                <path d="M6 12v5c3 3 9 3 12 0v-5" />
                            </svg>
                        </div>

                        <h3>{{ $j['nama'] }}</h3>

                        <p>{{ Str::limit(strip_tags($j['deskripsi']), 120) }}</p>

                        <a class="more" href="/pendidikan/penerimaan-mahasiswa-baru/{{ $j['tandha'] ?? '' }}"
                            aria-label="Lihat detail {{ $j['nama'] }}">
                            Lihat detail
                            <x-landing.ui.arrow />
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
