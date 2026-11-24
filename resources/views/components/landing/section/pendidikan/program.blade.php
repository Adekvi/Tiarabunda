@props(['program' => [], 'selectedProdi' => null])

@php
    $items = collect($program);

    // Helper untuk mengambil URL foto dari berbagai kemungkinan nama kolom di database
    $resolveImage = function ($item) {
        foreach (['gambar', 'foto', 'sampul', 'image', 'thumbnail', 'banner', 'img'] as $field) {
            $val = data_get($item, $field);
            if (is_string($val) && trim($val) !== '') {
                $val = trim($val);
                if (
                    str_starts_with($val, 'http://') ||
                    str_starts_with($val, 'https://') ||
                    str_starts_with($val, '/')
                ) {
                    return $val;
                }
                if (
                    str_starts_with($val, 'storage/') ||
                    str_starts_with($val, 'landing/') ||
                    str_starts_with($val, 'assets/')
                ) {
                    return asset($val);
                }
                return asset('storage/' . ltrim($val, '/'));
            }
        }
        return null;
    };

    // Normalisasi key fakultas & cek apakah URL sedang memilih fakultas (?fakultas=...)
    $requestedFak = trim((string) request()->query('fakultas', ''));
    $activeFakKey = null;

    if (!$selectedProdi && $requestedFak !== '' && $items->isNotEmpty()) {
        foreach ($items as $idx => $fakItem) {
            $candidateKey = trim((string) (data_get($fakItem, 'tandha') ?? (data_get($fakItem, 'id') ?? $idx)));
            if ($candidateKey === $requestedFak) {
                $activeFakKey = $candidateKey;
                break;
            }
        }
    }

    // Cari info fakultas induk jika sedang membuka halaman detail prodi ($selectedProdi)
    $parentFakKey = null;
    $parentFakName = data_get($selectedProdi, 'fakultas_jeneng');
    if ($selectedProdi && $items->isNotEmpty()) {
        $selectedFakId = data_get($selectedProdi, 'fakultas_id');
        foreach ($items as $idx => $fakItem) {
            $fakId = data_get($fakItem, 'id');
            $fakName = data_get($fakItem, 'jeneng');
            $fakProdis = collect(data_get($fakItem, 'activeProdis', data_get($fakItem, 'prodis', [])));

            $isMatch =
                ($selectedFakId !== null && (string) $fakId === (string) $selectedFakId) ||
                ($parentFakName && trim((string) $fakName) === trim((string) $parentFakName)) ||
                $fakProdis->contains(function ($p) use ($selectedProdi) {
                    return (string) data_get($p, 'id') === (string) data_get($selectedProdi, 'id');
                });

            if ($isMatch) {
                $parentFakKey = trim((string) (data_get($fakItem, 'tandha') ?? ($fakId ?? $idx)));
                $parentFakName = $fakName ?: $parentFakName;
                break;
            }
        }
    }
@endphp

<section class="prog" id="prog-root">
    <div class="wrap prog__wrap">

        {{-- ========================================================== --}}
        {{-- TAHAP 3: DETAIL PROGRAM STUDI (Saat kartu prodi diklik)     --}}
        {{-- ========================================================== --}}
        @if ($selectedProdi)
            @php
                $prodiImg = $resolveImage($selectedProdi);
                $backToFakUrl = $parentFakKey
                    ? '/pendidikan/program-pendidikan?fakultas=' . urlencode($parentFakKey)
                    : '/pendidikan/program-pendidikan';
            @endphp

            <div class="prog__detail rv">
                <div class="prog__nav-top">
                    <a href="{{ $backToFakUrl }}" class="prog__back">
                        <span aria-hidden="true">&larr;</span>
                        <span>Kembali ke {{ $parentFakName ?: 'Fakultas & Sekolah' }}</span>
                    </a>

                    <nav class="prog__crumb" aria-label="Breadcrumb">
                        <a href="/pendidikan/program-pendidikan">Fakultas &amp; Sekolah</a>
                        @if ($parentFakName)
                            <span>/</span>
                            <a href="{{ $backToFakUrl }}">{{ $parentFakName }}</a>
                        @endif
                        <span>/</span>
                        <span class="is-current">{{ data_get($selectedProdi, 'jeneng') }}</span>
                    </nav>
                </div>

                @if ($prodiImg)
                    <div class="prog__detail-media">
                        <img src="{{ $prodiImg }}" alt="{{ data_get($selectedProdi, 'jeneng') }}"
                            onerror="this.parentElement.style.display='none'">
                    </div>
                @endif

                <p class="prog__eyebrow">Detail Program Studi</p>
                <h2 class="prog__title">{{ data_get($selectedProdi, 'jeneng') }}</h2>

                <div class="prog__badges">
                    @if (data_get($selectedProdi, 'undhak'))
                        <span class="prog__jenjang">{{ data_get($selectedProdi, 'undhak') }}</span>
                    @endif
                    @if (data_get($selectedProdi, 'gelar'))
                        <span class="prog__pill-soft">Gelar: {{ data_get($selectedProdi, 'gelar') }}</span>
                    @endif
                    @if (data_get($selectedProdi->akreditasi ?? null, 'status_akred'))
                        <span class="prog__akred">Akreditasi
                            {{ data_get($selectedProdi->akreditasi, 'status_akred') }}</span>
                    @endif
                    @if ($parentFakName)
                        <span class="prog__pill-soft">{{ $parentFakName }}</span>
                    @endif
                </div>

                <div class="prog__detail-body">
                    @if (data_get($selectedProdi, 'katrangan'))
                        {!! nl2br(e(data_get($selectedProdi, 'katrangan'))) !!}
                    @else
                        <p>Informasi lebih lanjut mengenai program studi ini belum tersedia. Silakan hubungi bagian
                            akademik untuk detail kurikulum dan pendaftaran.</p>
                    @endif
                </div>
            </div>

            {{-- ========================================================== --}}
            {{-- TAHAP 1 & 2: DAFTAR FAKULTAS -> DETAIL FAKULTAS & PRODI    --}}
            {{-- ========================================================== --}}
        @else
            {{-- TAHAP 1: TAMPILAN SELURUH FAKULTAS & SEKOLAH --}}
            <div id="prog-fakultas-list" @if ($activeFakKey !== null) hidden @endif>
                <div class="prog__intro rv">
                    <p class="prog__eyebrow">Pendidikan</p>
                    <h2 class="prog__title">Fakultas &amp; Sekolah</h2>
                    <p class="prog__lead">
                        Pilih fakultas atau sekolah di bawah ini untuk melihat profil fakultas beserta daftar program
                        studi yang tersedia.
                    </p>
                </div>

                @if ($items->isNotEmpty())
                    <div class="prog__fak-grid">
                        @foreach ($items as $idx => $fak)
                            @php
                                $prodis = collect(data_get($fak, 'activeProdis', data_get($fak, 'prodis', [])));
                                $fakKey = trim((string) (data_get($fak, 'tandha') ?? (data_get($fak, 'id') ?? $idx)));
                                $fakImg = $resolveImage($fak);
                                $fakCode = data_get($fak, 'kodhe');
                                $fakName = data_get($fak, 'jeneng');
                                $fakDesc = data_get($fak, 'katrangan');
                            @endphp

                            <a href="/pendidikan/program-pendidikan?fakultas={{ urlencode($fakKey) }}"
                                class="prog__fak-card rv" data-select-fakultas="{{ $fakKey }}">
                                <div class="prog__fak-media {{ $fakImg ? '' : 'is-placeholder' }}">
                                    @if ($fakImg)
                                        <img src="{{ $fakImg }}" alt="{{ $fakName }}" loading="lazy"
                                            onerror="this.style.display='none'; this.parentElement.classList.add('is-placeholder');">
                                    @endif

                                    <div class="prog__fak-fallback" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" width="38" height="38" fill="none"
                                            stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                            <path d="M6 12v5c3 3 9 3 12 0v-5" />
                                        </svg>
                                    </div>

                                    <div class="prog__fak-overlay">
                                        @if ($fakCode)
                                            <span class="prog__fak-badge-code">{{ $fakCode }}</span>
                                        @else
                                            <span></span>
                                        @endif
                                        <span class="prog__fak-badge-count">{{ $prodis->count() }} Prodi</span>
                                    </div>
                                </div>

                                <div class="prog__fak-body">
                                    <h3 class="prog__fak-card-title">{{ $fakName }}</h3>

                                    <p class="prog__fak-card-desc">
                                        {{ $fakDesc ?: 'Jelajahi profil akademik dan pilihan program studi unggulan di lingkungan ' . $fakName . '.' }}
                                    </p>

                                    <span class="prog__fak-card-cta">
                                        <span>Lihat Fakultas &amp; Prodi</span>
                                        <span aria-hidden="true">&rarr;</span>
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="prog__empty-all rv">
                        <h3>Data fakultas belum tersedia</h3>
                        <p>Daftar fakultas dan program studi akan tampil setelah diisi melalui panel superadmin.</p>
                    </div>
                @endif
            </div>

            {{-- TAHAP 2: TAMPILAN FAKULTAS (Kiri: Deskripsi, Kanan: Foto, Bawah: Pilihan Prodi) --}}
            @foreach ($items as $idx => $fak)
                @php
                    $prodis = collect(data_get($fak, 'activeProdis', data_get($fak, 'prodis', [])));
                    $fakKey = trim((string) (data_get($fak, 'tandha') ?? (data_get($fak, 'id') ?? $idx)));
                    $fakImg = $resolveImage($fak);
                    $fakCode = data_get($fak, 'kodhe');
                    $fakName = data_get($fak, 'jeneng');
                    $fakDesc = data_get($fak, 'katrangan');
                    $isCurrentFak = $activeFakKey === $fakKey;
                @endphp

                <div class="prog__fak-view" data-fakultas-panel="{{ $fakKey }}"
                    @if (!$isCurrentFak) hidden @endif>
                    {{-- Navigasi Kembali & Breadcrumb --}}
                    <div class="prog__nav-top">
                        <a href="/pendidikan/program-pendidikan" class="prog__back" data-back-fakultas>
                            <span aria-hidden="true">&larr;</span>
                            <span>Kembali ke Semua Fakultas &amp; Sekolah</span>
                        </a>

                        <nav class="prog__crumb" aria-label="Breadcrumb">
                            <a href="/pendidikan/program-pendidikan" data-back-fakultas>Fakultas &amp; Sekolah</a>
                            <span>/</span>
                            <span class="is-current">{{ $fakName }}</span>
                        </nav>
                    </div>

                    {{-- Bagian Atas: Kiri Deskripsi Fakultas, Kanan Foto Fakultas --}}
                    <div class="prog__fak-hero">
                        <div class="prog__fak-hero-info">
                            <div class="prog__fak-hero-meta">
                                @if ($fakCode)
                                    <span class="prog__code">{{ $fakCode }}</span>
                                @endif
                                <span class="prog__count">{{ $prodis->count() }} Program Studi</span>
                            </div>

                            <h2 class="prog__fak-hero-title">{{ $fakName }}</h2>

                            <div class="prog__fak-hero-desc">
                                @if ($fakDesc)
                                    {!! nl2br(e($fakDesc)) !!}
                                @else
                                    <p>{{ $fakName }} menyelenggarakan pendidikan tinggi yang berorientasi pada
                                        penguasaan kompetensi profesional, praktik berbasis kebutuhan industri, serta
                                        pengembangan keilmuan yang aplikatif.</p>
                                @endif
                            </div>
                        </div>

                        <div class="prog__fak-hero-media {{ $fakImg ? '' : 'is-placeholder' }}">
                            @if ($fakImg)
                                <img src="{{ $fakImg }}" alt="{{ $fakName }}"
                                    onerror="this.style.display='none'; this.parentElement.classList.add('is-placeholder');">
                            @endif

                            <div class="prog__fak-fallback" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="52" height="52" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                    <path d="M6 12v5c3 3 9 3 12 0v-5" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Bagian Bawah: Pilihan Program Studi (Klik langsung ke Detail Prodi) --}}
                    <div class="prog__prodi-section">
                        <div class="prog__prodi-head">
                            <div>
                                <p class="prog__eyebrow">Akademik</p>
                                <h3>Pilihan Program Studi</h3>
                            </div>
                            <p class="prog__prodi-sub">Pilih program studi di bawah ini untuk melihat detail kurikulum,
                                gelar lulusan, dan informasi lengkapnya.</p>
                        </div>

                        @if ($prodis->isNotEmpty())
                            <div class="prog__grid">
                                @foreach ($prodis as $prodi)
                                    @php
                                        $status = data_get($prodi, 'akreditasi.status_akred');
                                        $gelar = data_get($prodi, 'gelar');
                                        $slug = data_get($prodi, 'tandha') ?? data_get($prodi, 'id');
                                    @endphp

                                    <a href="/pendidikan/program-pendidikan/{{ $slug }}" class="prog__card"
                                        id="{{ $slug }}">
                                        <div class="prog__card-top">
                                            @if (data_get($prodi, 'undhak'))
                                                <span class="prog__jenjang">{{ data_get($prodi, 'undhak') }}</span>
                                            @endif
                                            @if ($gelar)
                                                <span class="prog__gelar">{{ $gelar }}</span>
                                            @endif
                                            @if ($status)
                                                <span class="prog__akred">Akreditasi {{ $status }}</span>
                                            @endif
                                        </div>

                                        <h4 class="prog__prodi">{{ data_get($prodi, 'jeneng') }}</h4>

                                        @if (data_get($prodi, 'katrangan'))
                                            <p class="prog__prodi-desc">{{ data_get($prodi, 'katrangan') }}</p>
                                        @else
                                            <p class="prog__prodi-desc">Lihat profil lengkap, kompetensi lulusan, dan
                                                informasi akademik program studi {{ data_get($prodi, 'jeneng') }}.</p>
                                        @endif

                                        <div class="prog__card-foot">
                                            <span>Lihat Detail Program Studi</span>
                                            <span class="prog__card-arrow" aria-hidden="true">&rarr;</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="prog__empty">Belum ada program studi aktif pada fakultas ini.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif

    </div>
</section>

@if (!$selectedProdi && $items->isNotEmpty())
    <script>
        (() => {
            const root = document.getElementById('prog-root');
            const listView = document.getElementById('prog-fakultas-list');
            if (!root || !listView) return;

            const panels = [...root.querySelectorAll('[data-fakultas-panel]')];

            const scrollToTop = () => {
                const nav = document.getElementById('nav');
                const offset = (nav ? nav.offsetHeight : 74) + 20;
                const top = root.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({
                    top: Math.max(0, top),
                    behavior: 'smooth'
                });
            };

            const showFakultas = (key, push = true, shouldScroll = true) => {
                const cleanKey = key ? String(key).trim() : '';
                const targetPanel = cleanKey ?
                    panels.find((p) => (p.dataset.fakultasPanel || '').trim() === cleanKey) :
                    null;

                if (targetPanel) {
                    listView.hidden = true;
                    panels.forEach((p) => {
                        p.hidden = p !== targetPanel;
                    });

                    if (push) {
                        const url = new URL(window.location.href);
                        url.searchParams.set('fakultas', cleanKey);
                        window.history.pushState({
                            fakultas: cleanKey
                        }, '', url);
                    }
                } else {
                    panels.forEach((p) => {
                        p.hidden = true;
                    });
                    listView.hidden = false;

                    if (push) {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('fakultas');
                        window.history.pushState({
                            fakultas: null
                        }, '', url);
                    }
                }

                if (shouldScroll) scrollToTop();
            };

            root.addEventListener('click', (e) => {
                const fakCard = e.target.closest('[data-select-fakultas]');
                if (fakCard && !e.ctrlKey && !e.metaKey && !e.shiftKey) {
                    e.preventDefault();
                    showFakultas(fakCard.dataset.selectFakultas, true, true);
                    return;
                }

                const backBtn = e.target.closest('[data-back-fakultas]');
                if (backBtn && !e.ctrlKey && !e.metaKey && !e.shiftKey) {
                    e.preventDefault();
                    showFakultas(null, true, true);
                }
            });

            window.addEventListener('popstate', () => {
                const key = new URLSearchParams(window.location.search).get('fakultas');
                showFakultas(key, false, false);
            });
        })();
    </script>
@endif
