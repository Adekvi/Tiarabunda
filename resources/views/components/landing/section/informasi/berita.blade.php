@props(['beritaList' => [], 'selectedBerita' => null])

@php
    $items = collect($beritaList);
    $fmt = fn($d) => $d ? \Carbon\Carbon::parse($d)->translatedFormat('d M Y') : '';
@endphp

<section class="brt">
    <div class="wrap brt__wrap">

        {{-- ========================================== --}}
        {{-- HALAMAN DETAIL BERITA --}}
        {{-- ========================================== --}}
        @if ($selectedBerita)
            <article class="brt__detail rv">
                <div class="brt__back-wrap">
                    <a href="/informasi/berita-terbaru" class="brt__back-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Daftar Berita
                    </a>
                </div>

                <div class="brt__header">
                    <span class="brt__badge">{{ data_get($selectedBerita, 'golongan') }}</span>
                    <h1 class="brt__title">{{ data_get($selectedBerita, 'judhul') }}</h1>

                    <div class="brt__meta">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <span>{{ $fmt(data_get($selectedBerita, 'wektu_terbit')) }}</span>
                    </div>
                </div>

                @if (data_get($selectedBerita, 'sampul_url'))
                    <div class="brt__sampul">
                        <img src="{{ data_get($selectedBerita, 'sampul_url') }}"
                            alt="{{ data_get($selectedBerita, 'judhul') }}">
                    </div>
                @endif

                <div class="brt__content prose">
                    {!! nl2br(data_get($selectedBerita, 'isi')) !!}
                </div>
            </article>

            {{-- ========================================== --}}
            {{-- HALAMAN DAFTAR BERITA --}}
            {{-- ========================================== --}}
        @else
            <div class="brt__intro rv">
                <p class="brt__eyebrow">Kabar Kampus</p>
                <h2 class="brt__page-title">Berita Terbaru</h2>
                <p class="brt__lead">
                    Ikuti perkembangan, kegiatan, dan prestasi terbaru dari Universitas Tiara Bunda.
                </p>
            </div>

            @if ($items->isNotEmpty())
                <div class="brt__grid">
                    @foreach ($items as $b)
                        <article class="brt__card rv">
                            <a href="/informasi/berita-terbaru/{{ data_get($b, 'tandha') }}" class="brt__card-img">
                                @if (data_get($b, 'thumb_url'))
                                    <img src="{{ data_get($b, 'thumb_url') }}" alt="{{ data_get($b, 'judhul') }}"
                                        loading="lazy" decoding="async">
                                @else
                                    <div class="brt__img-placeholder">No Image</div>
                                @endif
                                <span class="brt__card-badge">{{ data_get($b, 'golongan') }}</span>
                            </a>

                            <div class="brt__card-body">
                                <div class="brt__card-date">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                    {{ $fmt(data_get($b, 'wektu_terbit')) }}
                                </div>

                                <h3 class="brt__card-title">
                                    <a href="/informasi/berita-terbaru/{{ data_get($b, 'tandha') }}">
                                        {{ data_get($b, 'judhul') }}
                                    </a>
                                </h3>

                                @if (data_get($b, 'ringkesan'))
                                    <p class="brt__card-desc">{{ Str::limit(data_get($b, 'ringkesan'), 120) }}</p>
                                @endif

                                <a href="/informasi/berita-terbaru/{{ data_get($b, 'tandha') }}"
                                    class="brt__card-link">
                                    Baca Selengkapnya <span aria-hidden="true">&rarr;</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="brt__empty rv">
                    <h3>Belum ada berita</h3>
                    <p>Berita terbaru akan segera dipublikasikan.</p>
                </div>
            @endif
        @endif

    </div>
</section>
