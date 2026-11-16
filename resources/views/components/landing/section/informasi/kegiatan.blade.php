@props(['kegiatanList' => [], 'selectedKegiatan' => null])

@php
    $items = collect($kegiatanList);

    // Helper format tanggal
    $parseDate = fn($d) => $d ? \Carbon\Carbon::parse($d) : null;
    $fmtDay = fn($d) => $parseDate($d)?->translatedFormat('d');
    $fmtMonth = fn($d) => $parseDate($d)?->translatedFormat('M');
    $fmtYear = fn($d) => $parseDate($d)?->translatedFormat('Y');
    $fmtTime = fn($d) => $parseDate($d)?->format('H:i');
    $fmtFull = fn($d) => $parseDate($d)?->translatedFormat('l, d F Y • H:i');
@endphp

<section class="kgt">
    <div class="wrap kgt__wrap">

        {{-- ========================================== --}}
        {{-- HALAMAN DETAIL KEGIATAN --}}
        {{-- ========================================== --}}
        @if ($selectedKegiatan)
            <article class="kgt__detail rv">
                <div class="kgt__back-wrap">
                    <a href="/informasi/kegiatan-kampus" class="kgt__back-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Agenda
                    </a>
                </div>

                <div class="kgt__header">
                    <div class="kgt__date-badge">
                        <span class="kgt__date-day">{{ $fmtDay(data_get($selectedKegiatan, 'wektu_mulai')) }}</span>
                        <span class="kgt__date-month">{{ $fmtMonth(data_get($selectedKegiatan, 'wektu_mulai')) }}</span>
                    </div>
                    <div>
                        <h1 class="kgt__title">{{ data_get($selectedKegiatan, 'judhul') }}</h1>

                        <div class="kgt__meta">
                            @if (data_get($selectedKegiatan, 'papan'))
                                <span class="kgt__meta-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                        <circle cx="12" cy="10" r="3" />
                                    </svg>
                                    {{ data_get($selectedKegiatan, 'papan') }}
                                </span>
                            @endif
                            <span class="kgt__meta-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                {{ $fmtFull(data_get($selectedKegiatan, 'wektu_mulai')) }}
                            </span>
                        </div>
                    </div>
                </div>

                @if (data_get($selectedKegiatan, 'sampul_url'))
                    <div class="kgt__sampul">
                        <img src="{{ data_get($selectedKegiatan, 'sampul_url') }}"
                            alt="{{ data_get($selectedKegiatan, 'judhul') }}">
                    </div>
                @endif

                <div class="kgt__content prose">
                    {!! nl2br(data_get($selectedKegiatan, 'katrangan')) !!}
                </div>
            </article>

            {{-- ========================================== --}}
            {{-- HALAMAN DAFTAR KEGIATAN --}}
            {{-- ========================================== --}}
        @else
            <div class="kgt__intro rv">
                <p class="kgt__eyebrow">Agenda Kampus</p>
                <h2 class="kgt__page-title">Kegiatan Kampus</h2>
                <p class="kgt__lead">
                    Jadwal kegiatan, seminar, dan agenda penting lainnya di lingkungan Universitas Tiara Bunda.
                </p>
            </div>

            @if ($items->isNotEmpty())
                <div class="kgt__grid">
                    @foreach ($items as $k)
                        <article class="kgt__card rv">
                            <div class="kgt__card-date">
                                <span class="kgt__card-day">{{ $fmtDay(data_get($k, 'wektu_mulai')) }}</span>
                                <div class="kgt__card-month-year">
                                    <span class="kgt__card-month">{{ $fmtMonth(data_get($k, 'wektu_mulai')) }}</span>
                                    <span class="kgt__card-year">{{ $fmtYear(data_get($k, 'wektu_mulai')) }}</span>
                                </div>
                            </div>

                            <div class="kgt__card-body">
                                <h3 class="kgt__card-title">
                                    <a href="/informasi/kegiatan-kampus/{{ data_get($k, 'tandha') }}">
                                        {{ data_get($k, 'judhul') }}
                                    </a>
                                </h3>

                                @if (data_get($k, 'papan'))
                                    <div class="kgt__card-loc">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                            <circle cx="12" cy="10" r="3" />
                                        </svg>
                                        {{ data_get($k, 'papan') }}
                                    </div>
                                @endif

                                <a href="/informasi/kegiatan-kampus/{{ data_get($k, 'tandha') }}"
                                    class="kgt__card-link">
                                    Detail Kegiatan &rarr;
                                </a>
                            </div>

                            <div class="kgt__card-time">
                                {{ $fmtTime(data_get($k, 'wektu_mulai')) }} WIB
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="kgt__empty rv">
                    <h3>Belum ada agenda</h3>
                    <p>Saat ini belum ada kegiatan terjadwal dalam waktu dekat.</p>
                </div>
            @endif
        @endif

    </div>
</section>
