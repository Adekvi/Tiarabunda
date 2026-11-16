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
    <section class="sec" id="berita">
        <div class="wrap">
            <x-landing.ui.head kick="Berita & kegiatan" title="Kabar terbaru dari kampus" />

            <div class="news rv">

                {{-- KARTU UTAMA (FEATURED) --}}
                <a href="/informasi/berita-terbaru/{{ $f['tandha'] ?? '' }}" class="nf"
                    style="text-decoration: none; color: inherit;">
                    @if (!empty($f['img']))
                        <div class="nf-cover">
                            <img src="{{ $f['img'] }}" alt="{{ $f['t'] ?? '' }}" loading="eager"
                                fetchpriority="high" decoding="sync">
                        </div>
                    @else
                        <div class="nf-cover"
                            style="display: flex; align-items: center; justify-content: center; background: #e5e7eb; color: #6b7280;">
                            Gambar Utama Tidak Tersedia
                        </div>
                    @endif

                    <div class="nf-body">
                        <span class="tag">{{ $f['j'] ?? 'Berita' }}</span>
                        <h3>{{ $f['t'] ?? '' }}</h3>
                        <time>{{ $f['tgl'] ?? '' }}</time>
                    </div>
                </a>

                {{-- DAFTAR BERITA KANAN --}}
                <div class="nl">
                    @foreach (array_slice($items, 1) as $n)
                        <a href="/informasi/berita-terbaru/{{ $n['tandha'] ?? '' }}" class="nl-item"
                            style="text-decoration: none; color: inherit; display: flex; gap: 16px; margin-bottom: 16px;">
                            @if (!empty($n['img']))
                                <div class="nl-thumb">
                                    <img src="{{ $n['img'] }}" alt="{{ $n['t'] ?? '' }}" loading="lazy">
                                </div>
                            @endif
                            <div class="nl-body">
                                <span class="tag">{{ $n['j'] ?? 'Berita' }}</span>
                                <h3>{{ $n['t'] ?? '' }}</h3>
                                <time>{{ $n['tgl'] ?? '' }}</time>
                            </div>
                        </a>
                    @endforeach
                </div>

            </div>

            {{-- Tambahkan tombol Lihat Semua Berita --}}
            <div style="text-align: center; margin-top: 40px;">
                <a href="/informasi/berita-terbaru" class="btn-outline"
                    style="display:inline-flex;align-items:center;gap:.5rem; padding:.75rem 1.5rem;border-radius:999px; font-weight:600;text-decoration:none;">
                    Lihat Semua Berita
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
@endif
