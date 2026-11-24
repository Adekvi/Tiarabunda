@props(['items' => []])

@php
    if ($items instanceof \Illuminate\Support\Collection) {
        $items = $items->all();
    }
    if ($items === [] || $items === null) {
        $items = app(\App\Services\Landing\LandingHomeService::class)->beritas()->all();
    }
    if ($items === []) {
        $items = config('landing.home.berita', []);
    }
    $items = array_values(is_array($items) ? $items : []);

    $trackItems = $items;
    if (count($trackItems) > 0 && count($trackItems) < 6) {
        while (count($trackItems) < 6) {
            $trackItems = array_merge($trackItems, $items);
        }
    }
@endphp

@if (count($items))
    <section class="sec berita-sec bg-band-h" id="berita" aria-labelledby="berita-title">
        <div class="berita-bg" aria-hidden="true">
            <div class="berita-bg-grid"></div>
            <div class="berita-bg-glow"></div>
        </div>

        <div class="wrap position-relative berita-head-wrap">
            <x-landing.ui.head kick="Info Terkini" title="Kabar terbaru dari kampus"
                lead="Ikuti perkembangan kegiatan, prestasi, dan informasi penting dari kampus." id="berita-title" />

            <div class="berita-meta" aria-label="Ringkasan informasi berita">
                <span class="berita-meta-badge">
                    <span class="berita-meta-dot" aria-hidden="true"></span>
                    <span><strong>{{ count($items) }}</strong> Kabar Terbaru</span>
                </span>
                <span class="berita-meta-tag">Kegiatan &amp; Prestasi</span>
            </div>
        </div>

        <div class="berita-gal rv position-relative" aria-label="Galeri berita terbaru kampus">
            <div class="berita-track">
                @foreach ([false, true] as $dup)
                    @foreach ($trackItems as $b)
                        @php
                            $href =
                                $b['url'] ??
                                url(
                                    '/informasi/berita-terbaru/' .
                                        ($b['tandha'] ?? \Illuminate\Support\Str::slug($b['t'] ?? '')),
                                );
                        @endphp
                        <a href="{{ $href }}" class="berita-card"
                            @if ($dup) aria-hidden="true" tabindex="-1" @endif>
                            <div class="berita-card-media">
                                @if (!empty($b['img']))
                                    <img src="{{ $b['img'] }}" alt="{{ $dup ? '' : $b['t'] ?? 'Berita kampus' }}"
                                        width="640" height="420" loading="lazy" decoding="async"
                                        onerror="this.parentElement.classList.add('is-empty'); this.remove();">
                                @else
                                    <div class="berita-placeholder" aria-hidden="true"></div>
                                @endif

                                @if (!empty($b['tgl']))
                                    <time class="berita-date-pill" datetime="{{ $b['tgl'] }}">
                                        {{ $b['tgl'] }}
                                    </time>
                                @endif

                                <div class="berita-overlay"></div>
                            </div>

                            <div class="berita-card-body">
                                <span class="berita-label">{{ $b['j'] ?? 'Berita' }}</span>
                                <h3 class="berita-title">{{ $b['t'] ?? 'Berita Kampus' }}</h3>
                                <span class="berita-link">
                                    Baca selengkapnya
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M5 12h14M13 6l6 6-6 6" />
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                @endforeach
            </div>
        </div>

        <div class="wrap position-relative berita-cta">
            <a href="{{ url('/informasi/berita-terbaru') }}" class="berita-btn">
                <span>Lihat semua berita</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </a>
        </div>
    </section>
@endif
