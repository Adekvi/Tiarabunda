@props([
    'highlight' => null,
    'items' => [],
    'selectedLppm' => null,
    'isPortalProgress' => false,
])

@php
    $items = collect($items);
    $baseUrl = '/lembaga/lppm';
    $portalUrl =
        data_get($highlight, 'data_tambahan.url_portal') ?:
        \App\Services\Landing\Lembaga\LppmService::PORTAL_PROGRESS_PATH;
    $portalDomain = \App\Services\Landing\Lembaga\LppmService::PORTAL_DISPLAY_DOMAIN;

    $getIcon = function ($ikon) {
        return match ($ikon) {
            'microscope'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18h8"/><path d="M3 22h18"/><path d="M14 22a7 7 0 1 0 0-14h-1"/><path d="M9 14h2"/><path d="M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2Z"/><path d="M12 6V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3"/></svg>',
            'users'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            'globe'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
            'book-open'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>',
            'shield'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
            default
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>',
        };
    };

    $getColor = function ($warna) {
        return match ($warna) {
            'green' => 'lppm__icon--green',
            'orange' => 'lppm__icon--orange',
            'red' => 'lppm__icon--red',
            'purple' => 'lppm__icon--purple',
            default => 'lppm__icon--blue',
        };
    };
@endphp

<section class="lppm">
    <div class="wrap lppm__wrap">

        {{-- ========================================================= --}}
        {{-- 1. TAMPILAN PORTAL LPPM ON PROGRESS (TAB BARU)            --}}
        {{-- ========================================================= --}}
        @if ($isPortalProgress)
            @php
                $hlMeta = data_get($highlight, 'data_tambahan', []);
            @endphp

            <div class="lppm__prog-card rv">
                {{-- Header Browser Bar --}}
                <div class="lppm__prog-topbar">
                    <div class="lppm__prog-dots" aria-hidden="true">
                        <span></span><span></span><span></span>
                    </div>
                    <div class="lppm__prog-url">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                        <span>{{ $portalDomain }}</span>
                    </div>
                    <span class="lppm__prog-tag">On Progress</span>
                </div>

                <div class="lppm__prog-body">
                    <div class="lppm__prog-pill">
                        <span class="lppm__prog-pulse" aria-hidden="true"></span>
                        Sedang Dalam Tahap Pengerjaan
                    </div>

                    <div class="lppm__prog-icon" aria-hidden="true">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                        </svg>
                    </div>

                    <h2 class="lppm__prog-title">Portal Sistem Informasi LPPM Sedang Dikembangkan</h2>
                    <p class="lppm__prog-desc">
                        Layanan digital <strong>SIMLITABMAS &amp; Portal LPPM Universitas Tiara Bunda</strong> sedang
                        dalam tahap pengerjaan dan integrasi sistem untuk memudahkan pengajuan proposal riset,
                        pengabdian masyarakat, publikasi jurnal, serta layanan HKI.
                    </p>

                    {{-- Progress Bar --}}
                    <div class="lppm__prog-meter">
                        <div class="lppm__prog-meter-head">
                            <span>Progres Pengembangan Sistem</span>
                            <strong>75% &bull; Integrasi Modul</strong>
                        </div>
                        <div class="lppm__prog-track" role="progressbar" aria-valuenow="75" aria-valuemin="0"
                            aria-valuemax="100">
                            <div class="lppm__prog-fill" style="width: 75%;"></div>
                        </div>
                    </div>

                    {{-- Tahapan Pengerjaan --}}
                    <div class="lppm__prog-steps">
                        <div class="lppm__prog-step is-done">
                            <span class="lppm__prog-step-icon">&#10003;</span>
                            <div>
                                <strong>Arsitektur &amp; Basis Data</strong>
                                <small>Selesai</small>
                            </div>
                        </div>
                        <div class="lppm__prog-step is-active">
                            <span class="lppm__prog-step-icon">2</span>
                            <div>
                                <strong>Modul Hibah, PkM &amp; Jurnal</strong>
                                <small>Sedang Dikerjakan</small>
                            </div>
                        </div>
                        <div class="lppm__prog-step">
                            <span class="lppm__prog-step-icon">3</span>
                            <div>
                                <strong>Uji Coba &amp; Rilis Publik</strong>
                                <small>Tahap Berikutnya</small>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Sekretariat Sementara --}}
                    <div class="lppm__prog-info">
                        <div>
                            <small>Sekretariat Layanan Langsung</small>
                            <strong>{{ $hlMeta['lokasi'] ?? 'Gedung Rektorat Lt. 2, Ruang LPPM' }}</strong>
                        </div>
                        <div>
                            <small>Jam Operasional</small>
                            <strong>{{ $hlMeta['jam_layanan'] ?? 'Senin–Jumat 08.00–16.00 WIB' }}</strong>
                        </div>
                        <div>
                            <small>Email Korespondensi</small>
                            <strong>{{ $hlMeta['email'] ?? 'lppm@tiarabunda.ac.id' }}</strong>
                        </div>
                    </div>

                    <div class="lppm__prog-actions">
                        <a href="{{ $baseUrl }}" class="lppm__portal-btn">
                            &larr; Kembali ke Halaman LPPM
                        </a>
                        <a href="/kontak" class="lppm__back-btn">
                            Hubungi Sekretariat LPPM
                        </a>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- 2. HALAMAN DETAIL PROGRAM / LAYANAN LPPM                  --}}
            {{-- ========================================================= --}}
        @elseif ($selectedLppm)
            @php
                $meta = data_get($selectedLppm, 'data_tambahan', []);
            @endphp

            <div class="lppm__back-wrap rv">
                <a href="{{ $baseUrl }}" class="lppm__back-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                    Kembali ke LPPM
                </a>
            </div>

            <article class="lppm__detail-box rv">
                <div class="lppm__detail-head">
                    <div class="lppm__card-icon {{ $getColor($meta['warna'] ?? 'blue') }}">
                        {!! $getIcon($meta['ikon'] ?? 'award') !!}
                    </div>
                    <div>
                        <p class="lppm__eyebrow" style="margin-bottom: 8px;">
                            {{ data_get($selectedLppm, 'kategori_label', 'Program LPPM') }}
                        </p>
                        <h2 class="lppm__detail-title">{{ data_get($selectedLppm, 'judhul') }}</h2>
                    </div>
                </div>

                <div class="lppm__detail-body prose">
                    {!! data_get($selectedLppm, 'isi') !!}
                </div>

                @if (
                    !empty($meta['jadwal']) ||
                        !empty($meta['lokasi']) ||
                        !empty($meta['kontak']) ||
                        !empty($meta['email']) ||
                        !empty($meta['url_portal']))
                    <div class="lppm__meta-bar">
                        @if (!empty($meta['jadwal']))
                            <div class="lppm__meta-item">
                                <small>Jadwal / Periode</small>
                                <strong>{{ $meta['jadwal'] }}</strong>
                            </div>
                        @endif
                        @if (!empty($meta['lokasi']))
                            <div class="lppm__meta-item">
                                <small>Lokasi Layanan</small>
                                <strong>{{ $meta['lokasi'] }}</strong>
                            </div>
                        @endif
                        @if (!empty($meta['kontak']))
                            <div class="lppm__meta-item">
                                <small>Kontak</small>
                                <strong>{{ $meta['kontak'] }}</strong>
                            </div>
                        @endif
                        @if (!empty($meta['email']))
                            <div class="lppm__meta-item">
                                <small>Email</small>
                                <strong>{{ $meta['email'] }}</strong>
                            </div>
                        @endif
                        @if (!empty($meta['url_portal']))
                            <div class="lppm__meta-item">
                                <small>Portal Sistem</small>
                                <a href="{{ $meta['url_portal'] }}" target="_blank" rel="noopener noreferrer">
                                    Buka Portal &rarr;
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </article>

            @php
                $otherPrograms = $items->reject(fn($i) => data_get($i, 'tandha') === data_get($selectedLppm, 'tandha'));
            @endphp

            @if ($otherPrograms->isNotEmpty())
                <div class="lppm__related rv">
                    <h3 class="lppm__related-title">Program &amp; Layanan LPPM Lainnya</h3>
                    <div class="lppm__grid">
                        @foreach ($otherPrograms as $other)
                            <article class="lppm__card">
                                <div class="lppm__card-top">
                                    <div
                                        class="lppm__card-icon {{ $getColor(data_get($other, 'data_tambahan.warna')) }}">
                                        {!! $getIcon(data_get($other, 'data_tambahan.ikon')) !!}
                                    </div>
                                    <span class="lppm__badge">{{ data_get($other, 'kategori_label') }}</span>
                                </div>
                                <h4 class="lppm__card-title">{{ data_get($other, 'judhul') }}</h4>
                                <div class="lppm__card-desc lppm__card-desc--clamp prose">
                                    {!! data_get($other, 'isi') !!}
                                </div>
                                <div class="lppm__card-footer">
                                    <a href="{{ $baseUrl }}/{{ data_get($other, 'tandha') }}"
                                        class="lppm__card-link">
                                        Lihat Selengkapnya
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

            {{-- ========================================================= --}}
            {{-- 3. HALAMAN UTAMA LPPM                                     --}}
            {{-- ========================================================= --}}
        @else
            <div class="lppm__intro rv">
                <p class="lppm__eyebrow">Riset &amp; Pengabdian</p>
                <h2 class="lppm__title">Lembaga Penelitian dan Pengabdian kepada Masyarakat</h2>
                <p class="lppm__lead">
                    Pusat pengelolaan penelitian terapan, pengabdian kepada masyarakat, publikasi ilmiah, serta
                    perlindungan kekayaan intelektual sivitas akademika Universitas Tiara Bunda.
                </p>

                <div class="lppm__intro-actions">
                    <a href="{{ $portalUrl }}" target="_blank" rel="noopener noreferrer"
                        class="lppm__portal-btn">
                        <span>Portal SIMLITABMAS / LPPM</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                            <polyline points="15 3 21 3 21 9" />
                            <line x1="10" y1="14" x2="21" y2="3" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Highlight Profil LPPM --}}
            @if ($highlight)
                @php
                    $hlMeta = data_get($highlight, 'data_tambahan', []);
                @endphp
                <div class="lppm__highlight rv">
                    <div class="lppm__hl-icon">
                        {!! $getIcon($hlMeta['ikon'] ?? 'award') !!}
                    </div>
                    <div class="lppm__hl-content">
                        <h3>{{ data_get($highlight, 'judhul') }}</h3>
                        <div class="lppm__hl-text prose">
                            {!! data_get($highlight, 'isi') !!}
                        </div>

                        @if (!empty($hlMeta['lokasi']) || !empty($hlMeta['jam_layanan']) || !empty($hlMeta['email']))
                            <div class="lppm__hl-meta">
                                @if (!empty($hlMeta['lokasi']))
                                    <span><strong>Sekretariat:</strong> {{ $hlMeta['lokasi'] }}</span>
                                @endif
                                @if (!empty($hlMeta['jam_layanan']))
                                    <span><strong>Jam Layanan:</strong> {{ $hlMeta['jam_layanan'] }}</span>
                                @endif
                                @if (!empty($hlMeta['email']))
                                    <span><strong>Email:</strong> {{ $hlMeta['email'] }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Pilar Utama LPPM (Statis) --}}
            <div class="lppm__pillars rv">
                <div class="lppm__pillar">
                    <span class="lppm__pillar-num">01</span>
                    <h4>Penelitian Terapan</h4>
                    <p>Pengembangan riset kolaboratif dosen dan mahasiswa yang berorientasi pada solusi nyata industri
                        dan masyarakat.</p>
                </div>
                <div class="lppm__pillar">
                    <span class="lppm__pillar-num">02</span>
                    <h4>Pengabdian Masyarakat</h4>
                    <p>Hilirisasi ilmu pengetahuan melalui KKN tematik, desa binaan, dan pemberdayaan UMKM mitra kampus.
                    </p>
                </div>
                <div class="lppm__pillar">
                    <span class="lppm__pillar-num">03</span>
                    <h4>Publikasi &amp; HKI</h4>
                    <p>Penerbitan jurnal ilmiah berkala terakreditasi serta pendampingan pendaftaran Hak Cipta dan
                        Paten.</p>
                </div>
            </div>

            {{-- Grid Program & Layanan LPPM --}}
            @if ($items->isNotEmpty())
                <div class="lppm__grid">
                    @foreach ($items as $item)
                        <article class="lppm__card rv">
                            <div class="lppm__card-top">
                                <div class="lppm__card-icon {{ $getColor(data_get($item, 'data_tambahan.warna')) }}">
                                    {!! $getIcon(data_get($item, 'data_tambahan.ikon')) !!}
                                </div>
                                <span class="lppm__badge">{{ data_get($item, 'kategori_label') }}</span>
                            </div>

                            <h3 class="lppm__card-title">{{ data_get($item, 'judhul') }}</h3>
                            <div class="lppm__card-desc lppm__card-desc--clamp prose">
                                {!! data_get($item, 'isi') !!}
                            </div>

                            <div class="lppm__card-footer">
                                <a href="{{ $baseUrl }}/{{ data_get($item, 'tandha') }}"
                                    class="lppm__card-link">
                                    Lihat Selengkapnya
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
            @endif
        @endif

        {{-- CTA --}}
        @if (!$isPortalProgress)
            <div class="lppm__cta rv">
                <div>
                    <h3>Konsultasi Proposal Riset, PkM, atau Pendaftaran HKI?</h3>
                    <p>Hubungi sekretariat LPPM untuk pendampingan penyusunan proposal hibah dan publikasi ilmiah.</p>
                </div>
                <a class="btn" href="#">Hubungi Sekretariat LPPM &rarr;</a>
            </div>
        @endif

    </div>
</section>
