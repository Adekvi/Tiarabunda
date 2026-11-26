@props([
    'highlight' => null,
    'items' => [],
    'allItems' => [],
    'kategoriList' => [],
    'selectedLpm' => null,
])

@php
    $items = collect($items)->values();
    $allItems = collect($allItems)->values();
    if ($allItems->isEmpty()) {
        $allItems = collect([$highlight])
            ->filter()
            ->merge($items)
            ->values();
    }

    $ppeppSteps = [
        ['code' => 'P', 'title' => 'Penetapan', 'desc' => 'Perumusan dan penetapan standar mutu pendidikan tinggi.'],
        [
            'code' => 'P',
            'title' => 'Pelaksanaan',
            'desc' => 'Implementasi standar mutu pada seluruh unit & program studi.',
        ],
        ['code' => 'E', 'title' => 'Evaluasi', 'desc' => 'Pelaksanaan Audit Mutu Internal (AMI) & survei kepuasan.'],
        ['code' => 'P', 'title' => 'Pengendalian', 'desc' => 'Rapat Tinjauan Manajemen (RTM) & tindak lanjut temuan.'],
        ['code' => 'P', 'title' => 'Peningkatan', 'desc' => 'Pembaruan standar mutu secara berkelanjutan.'],
    ];

    $badgeTone = function (?string $kategori): string {
        return match ($kategori) {
            'profil' => 'lpm__badge--navy',
            'spmi' => 'lpm__badge--blue',
            'ami' => 'lpm__badge--orange',
            'akreditasi' => 'lpm__badge--green',
            'monev' => 'lpm__badge--purple',
            default => 'lpm__badge--blue',
        };
    };

    $kontakCfg = config('landing.site.kontak', []);
    $waUrl = (string) ($kontakCfg['wa'] ?? 'https://wa.me/6281117014000');
    $namaKampus = (string) config('landing.site.nama', 'Universitas Tiara Bunda');
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('landing/menu/lembaga/lpm/lpm.css') }}">
@endpush

<section class="lpm" id="lpm-section" data-lpm-root>
    <div class="wrap lpm__wrap">

        @if ($selectedLpm)
            @php
                $meta = (array) data_get($selectedLpm, 'data_tambahan', []);
                $relatedItems = $allItems
                    ->reject(fn($row) => data_get($row, 'tandha') === data_get($selectedLpm, 'tandha'))
                    ->take(3)
                    ->values();
            @endphp

            {{-- ==================== HALAMAN DETAIL LPM ==================== --}}
            <div class="lpm__back-wrap rv">
                <a href="{{ url('/lembaga/lpm') }}" class="lpm__back-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                    <span>Kembali ke Lembaga Penjaminan Mutu</span>
                </a>
            </div>

            <article class="lpm__detail rv">
                <div class="lpm__detail-head">
                    <span class="lpm__badge {{ $badgeTone(data_get($selectedLpm, 'kategori')) }}">
                        {{ data_get($selectedLpm, 'kategori_label') }}
                    </span>
                    <h2 class="lpm__detail-title">{{ data_get($selectedLpm, 'judhul') }}</h2>

                    @if (
                        !empty($meta['nomor_dokumen']) ||
                            !empty($meta['tahun']) ||
                            !empty($meta['siklus']) ||
                            !empty($meta['lokasi']) ||
                            !empty($meta['penanggung_jawab']))
                        <div class="lpm__meta-grid">
                            @if (!empty($meta['nomor_dokumen']))
                                <div class="lpm__meta-item">
                                    <small>Nomor Dokumen</small>
                                    <strong>{{ $meta['nomor_dokumen'] }}</strong>
                                </div>
                            @endif
                            @if (!empty($meta['tahun']))
                                <div class="lpm__meta-item">
                                    <small>Tahun / Periode</small>
                                    <strong>{{ $meta['tahun'] }}</strong>
                                </div>
                            @endif
                            @if (!empty($meta['siklus']))
                                <div class="lpm__meta-item">
                                    <small>Siklus Mutu</small>
                                    <strong>{{ $meta['siklus'] }}</strong>
                                </div>
                            @endif
                            @if (!empty($meta['penanggung_jawab']))
                                <div class="lpm__meta-item">
                                    <small>Unit / Penanggung Jawab</small>
                                    <strong>{{ $meta['penanggung_jawab'] }}</strong>
                                </div>
                            @endif
                            @if (!empty($meta['lokasi']))
                                <div class="lpm__meta-item">
                                    <small>Lokasi Sekretariat</small>
                                    <strong>{{ $meta['lokasi'] }}</strong>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                @if (data_get($selectedLpm, 'isi'))
                    <div class="lpm__detail-body prose">
                        {!! data_get($selectedLpm, 'isi') !!}
                    </div>
                @endif

                @if (data_get($selectedLpm, 'has_file') && data_get($selectedLpm, 'file_url'))
                    <div class="lpm__file-box">
                        <div class="lpm__file-info">
                            <span class="lpm__file-ext">{{ data_get($selectedLpm, 'file_ext', 'PDF') }}</span>
                            <div>
                                <strong>Dokumen Lampiran Resmi</strong>
                                <p>Ukuran berkas: {{ data_get($selectedLpm, 'file_size') }}</p>
                            </div>
                        </div>
                        <div class="lpm__file-actions">
                            <a href="{{ data_get($selectedLpm, 'file_url') }}" target="_blank" rel="noopener"
                                class="lpm__btn lpm__btn--primary">
                                Buka / Unduh Dokumen &rarr;
                            </a>
                        </div>
                    </div>
                @endif
            </article>

            @if ($relatedItems->isNotEmpty())
                <div class="lpm__related rv">
                    <div class="lpm__section-head">
                        <span class="lpm__eyebrow">Arsip &amp; Informasi Mutu</span>
                        <h3>Dokumen &amp; Program LPM Lainnya</h3>
                    </div>

                    <div class="lpm__grid">
                        @foreach ($relatedItems as $rel)
                            <article class="lpm__card">
                                <div class="lpm__card-top">
                                    <span class="lpm__badge {{ $badgeTone(data_get($rel, 'kategori')) }}">
                                        {{ data_get($rel, 'kategori_label') }}
                                    </span>
                                    @if (data_get($rel, 'has_file'))
                                        <span class="lpm__file-pill">{{ data_get($rel, 'file_ext') }}</span>
                                    @endif
                                </div>

                                <h4 class="lpm__card-title">{{ data_get($rel, 'judhul') }}</h4>

                                @if (data_get($rel, 'isi'))
                                    <p class="lpm__card-desc">
                                        {{ \Illuminate\Support\Str::limit(strip_tags((string) data_get($rel, 'isi')), 130) }}
                                    </p>
                                @endif

                                <div class="lpm__card-footer">
                                    <a href="{{ url('/lembaga/lpm/' . data_get($rel, 'tandha')) }}"
                                        class="lpm__card-link">
                                        <span>Lihat Detail</span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            {{-- ==================== HALAMAN UTAMA LPM ==================== --}}
            <div class="lpm__intro rv">
                <span class="lpm__eyebrow">Penjaminan Mutu Pendidikan Tinggi</span>
                <h2 class="lpm__title">Lembaga Penjaminan Mutu (LPM)</h2>
                <p class="lpm__lead">
                    Mengawal budaya mutu akademik dan tata kelola institusi secara berkelanjutan melalui siklus
                    <strong>PPEPP</strong> (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan).
                </p>
            </div>

            {{-- Highlight Profil LPM (jika ada kategori 'profil') --}}
            @if ($highlight)
                @php
                    $hlMeta = (array) data_get($highlight, 'data_tambahan', []);
                @endphp
                <div class="lpm__highlight rv">
                    <div class="lpm__hl-main">
                        <span class="lpm__badge lpm__badge--gold">
                            {{ data_get($highlight, 'kategori_label', 'Profil & Struktur LPM') }}
                        </span>
                        <h3>{{ data_get($highlight, 'judhul') }}</h3>

                        @if (data_get($highlight, 'isi'))
                            <div class="lpm__hl-prose prose">
                                {!! data_get($highlight, 'isi') !!}
                            </div>
                        @endif

                        <div class="lpm__hl-actions">
                            <a href="{{ url('/lembaga/lpm/' . data_get($highlight, 'tandha')) }}"
                                class="lpm__btn lpm__btn--gold">
                                Selengkapnya &rarr;
                            </a>
                            @if (data_get($highlight, 'has_file') && data_get($highlight, 'file_url'))
                                <a href="{{ data_get($highlight, 'file_url') }}" target="_blank" rel="noopener"
                                    class="lpm__btn lpm__btn--ghost">
                                    Unduh Profil ({{ data_get($highlight, 'file_ext') }})
                                </a>
                            @endif
                        </div>
                    </div>

                    @if (!empty($hlMeta))
                        <div class="lpm__hl-side">
                            @if (!empty($hlMeta['nomor_dokumen']))
                                <div class="lpm__hl-stat">
                                    <small>Dokumen Dasar</small>
                                    <strong>{{ $hlMeta['nomor_dokumen'] }}</strong>
                                </div>
                            @endif
                            @if (!empty($hlMeta['siklus']))
                                <div class="lpm__hl-stat">
                                    <small>Kerangka Kerja</small>
                                    <strong>{{ $hlMeta['siklus'] }}</strong>
                                </div>
                            @endif
                            @if (!empty($hlMeta['lokasi']))
                                <div class="lpm__hl-stat">
                                    <small>Sekretariat LPM</small>
                                    <strong>{{ $hlMeta['lokasi'] }}</strong>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            {{-- Siklus PPEPP Strip --}}
            <div class="lpm__ppepp rv" aria-label="Siklus Penjaminan Mutu PPEPP">
                @foreach ($ppeppSteps as $idx => $step)
                    <div class="lpm__ppepp-step">
                        <div class="lpm__ppepp-num">{{ $idx + 1 }}. {{ $step['code'] }}</div>
                        <h4>{{ $step['title'] }}</h4>
                        <p>{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Filter Kategori & Pencarian Dokumen --}}
            <div class="lpm__toolbar rv">
                <div class="lpm__tabs" role="group" aria-label="Filter kategori LPM">
                    <button type="button" class="lpm__tab is-active" data-lpm-filter="all">
                        Semua ({{ $items->count() }})
                    </button>
                    @foreach ($kategoriList as $katKey => $katLabel)
                        @php
                            $countKat = $items->where('kategori', $katKey)->count();
                        @endphp
                        @if ($countKat > 0)
                            <button type="button" class="lpm__tab" data-lpm-filter="{{ $katKey }}">
                                {{ $katLabel }} ({{ $countKat }})
                            </button>
                        @endif
                    @endforeach
                </div>

                <div class="lpm__search">
                    <input type="search" class="lpm__search-input" placeholder="Cari dokumen SPMI, AMI, akreditasi..."
                        aria-label="Cari dokumen LPM" data-lpm-search>
                </div>
            </div>

            {{-- Grid Dokumen & Layanan Mutu --}}
            @if ($items->isNotEmpty())
                <div class="lpm__grid" data-lpm-grid>
                    @foreach ($items as $item)
                        @php
                            $meta = (array) data_get($item, 'data_tambahan', []);
                            $searchBlob = strtolower(
                                (string) data_get($item, 'judhul', '') .
                                    ' ' .
                                    (string) data_get($item, 'kategori_label', '') .
                                    ' ' .
                                    strip_tags((string) data_get($item, 'isi', '')) .
                                    ' ' .
                                    ($meta['nomor_dokumen'] ?? '') .
                                    ' ' .
                                    ($meta['tahun'] ?? ''),
                            );
                        @endphp
                        <article class="lpm__card rv" data-lpm-card data-kategori="{{ data_get($item, 'kategori') }}"
                            data-search="{{ $searchBlob }}">

                            <div class="lpm__card-top">
                                <span class="lpm__badge {{ $badgeTone(data_get($item, 'kategori')) }}">
                                    {{ data_get($item, 'kategori_label') }}
                                </span>

                                @if (data_get($item, 'has_file'))
                                    <span class="lpm__file-pill">
                                        {{ data_get($item, 'file_ext') }} &bull; {{ data_get($item, 'file_size') }}
                                    </span>
                                @elseif (!empty($meta['tahun']))
                                    <span class="lpm__year-pill">{{ $meta['tahun'] }}</span>
                                @endif
                            </div>

                            <h3 class="lpm__card-title">{{ data_get($item, 'judhul') }}</h3>

                            @if (!empty($meta['nomor_dokumen']))
                                <div class="lpm__card-code">No: {{ $meta['nomor_dokumen'] }}</div>
                            @endif

                            @if (data_get($item, 'isi'))
                                <p class="lpm__card-desc">
                                    {{ \Illuminate\Support\Str::limit(strip_tags((string) data_get($item, 'isi')), 140) }}
                                </p>
                            @endif

                            <div class="lpm__card-footer">
                                <a href="{{ url('/lembaga/lpm/' . data_get($item, 'tandha')) }}"
                                    class="lpm__card-link">
                                    <span>Baca Detail</span>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </a>

                                @if (data_get($item, 'has_file') && data_get($item, 'file_url'))
                                    <a href="{{ data_get($item, 'file_url') }}" target="_blank" rel="noopener"
                                        class="lpm__dl-btn" title="Unduh dokumen {{ data_get($item, 'judhul') }}">
                                        Unduh
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="lpm__empty" data-lpm-empty hidden>
                    <h3>Dokumen tidak ditemukan</h3>
                    <p>Silakan pilih kategori lain atau ubah kata kunci pencarian Anda.</p>
                </div>
            @endif

            {{-- CTA Box --}}
            <div class="lpm__cta rv">
                <div>
                    <h3>Butuh informasi dokumen mutu atau akreditasi?</h3>
                    <p>Hubungi Sekretariat Lembaga Penjaminan Mutu {{ $namaKampus }} untuk koordinasi SPMI, AMI, dan
                        data akreditasi.</p>
                </div>
                <a class="lpm__btn lpm__btn--gold"
                    href="{{ $waUrl }}?text={{ rawurlencode('Halo ' . $namaKampus . ', saya ingin menanyakan informasi terkait Lembaga Penjaminan Mutu (LPM).') }}"
                    target="_blank" rel="noopener">
                    Hubungi Sekretariat LPM &rarr;
                </a>
            </div>
        @endif

    </div>
</section>
