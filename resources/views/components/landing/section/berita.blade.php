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
@endphp

@if (count($items))
    @php($f = $items[0])
    <section class="sec berita-sec" id="berita">
        <div class="wrap">
            <x-landing.ui.head kick="Berita & kegiatan" title="Kabar terbaru dari kampus"
                lead="Ikuti perkembangan kegiatan, prestasi, dan informasi penting dari kampus." />

            <div class="news rv">
                {{-- Featured --}}
                <a href="/informasi/berita-terbaru/{{ $f['tandha'] ?? '' }}" class="nf">
                    @if (!empty($f['img']))
                        <div class="nf-cover">
                            <img src="{{ $f['img'] }}" alt="{{ $f['t'] ?? 'Berita utama' }}" loading="eager"
                                fetchpriority="high" decoding="async">
                        </div>
                    @else
                        <div class="nf-cover nf-cover--empty" aria-hidden="true">
                            <span>Gambar tidak tersedia</span>
                        </div>
                    @endif

                    <div class="nf-body">
                        <span class="nf-tag">{{ $f['j'] ?? 'Berita' }}</span>
                        <h3 class="nf-title">{{ $f['t'] ?? '' }}</h3>
                        <time class="nf-date" datetime="{{ $f['tgl'] ?? '' }}">{{ $f['tgl'] ?? '' }}</time>
                    </div>
                </a>

                {{-- List --}}
                <div class="nl">
                    @foreach (array_slice($items, 1) as $n)
                        <a href="/informasi/berita-terbaru/{{ $n['tandha'] ?? '' }}" class="nl-item">
                            @if (!empty($n['img']))
                                <div class="nl-thumb">
                                    <img src="{{ $n['img'] }}" alt="{{ $n['t'] ?? '' }}" loading="lazy"
                                        decoding="async">
                                </div>
                            @else
                                <div class="nl-thumb nl-thumb--empty" aria-hidden="true"></div>
                            @endif

                            <div class="nl-body">
                                <span class="nl-tag">{{ $n['j'] ?? 'Berita' }}</span>
                                <h3 class="nl-title">{{ $n['t'] ?? '' }}</h3>
                                <time class="nl-date">{{ $n['tgl'] ?? '' }}</time>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="berita-cta">
                <a href="/informasi/berita-terbaru" class="berita-btn">
                    <span>Lihat semua berita</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
@endif
