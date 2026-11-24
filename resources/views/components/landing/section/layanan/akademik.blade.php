@props([
    'items' => [],
    'kategoriList' => [],
])

@php
    $items = collect($items);
    $kategoriList = collect($kategoriList);

    if ($kategoriList->isEmpty() && $items->isNotEmpty()) {
        $kategoriList = $items
            ->map(fn($doc) => trim((string) (data_get($doc, 'kategori') ?: 'Umum')))
            ->unique()
            ->values();
    }

    $kategoriCounts = $items
        ->groupBy(fn($doc) => Str::slug(trim((string) (data_get($doc, 'kategori') ?: 'Umum'))))
        ->map->count();
@endphp

<section class="akd" id="akd-section">
    <div class="wrap akd__wrap">

        {{-- Header Intro --}}
        <div class="akd__intro rv">
            <p class="akd__eyebrow">Biro Administrasi Akademik</p>
            <h2 class="akd__title">Layanan &amp; Formulir Akademik</h2>
            <p class="akd__lead">
                Unduh templat formulir administrasi perkuliahan dalam format Microsoft Word (<strong>.docx /
                    .doc</strong>)
                seperti Surat Keterangan Aktif Kuliah, Pengajuan Cuti, hingga Pendaftaran Sidang Tugas Akhir.
            </p>
        </div>

        {{-- Info Jam Layanan & Kontak BAAK --}}
        <div class="akd__meta-bar rv">
            <div class="akd__meta-item">
                <small>Loket Layanan BAAK</small>
                <strong>Kampus A &amp; Kampus B — Gedung Rektorat Lt. 1</strong>
            </div>
            <div class="akd__meta-item">
                <small>Jam Operasional</small>
                <strong>Senin – Jumat, 08.00 – 16.00 WIB</strong>
            </div>
            <div class="akd__meta-item">
                <small>Konsultasi Akademik</small>
                <a href="https://wa.me/6281117014000" target="_blank" rel="noopener noreferrer">
                    0811-1701-4000 (WhatsApp) &rarr;
                </a>
            </div>
        </div>

        {{-- Filter Kategori & Pencarian Instan --}}
        @if ($items->isNotEmpty())
            <div class="akd__toolbar">
                <div class="akd__tabs" role="tablist" aria-label="Filter kategori formulir">
                    <button type="button" class="akd__tab is-active" data-akd-cat="all" aria-selected="true">
                        Semua Formulir ({{ $items->count() }})
                    </button>
                    @foreach ($kategoriList as $kat)
                        @php
                            $katClean = trim((string) ($kat ?: 'Umum'));
                            $katSlug = Str::slug($katClean);
                            $katCount = $kategoriCounts[$katSlug] ?? 0;
                        @endphp
                        <button type="button" class="akd__tab" data-akd-cat="{{ $katSlug }}"
                            aria-selected="false">
                            {{ $katClean }} ({{ $katCount }})
                        </button>
                    @endforeach
                </div>

                <div class="akd__search">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7" />
                        <path d="M20 20l-3.5-3.5" />
                    </svg>
                    <input type="search" id="akd-search-input" placeholder="Cari surat atau formulir…"
                        aria-label="Cari formulir akademik">
                </div>
            </div>

            {{-- Grid Kartu Formulir Word --}}
            <div class="akd__grid" id="akd-grid">
                @foreach ($items as $doc)
                    @php
                        $kategoriLabel = trim((string) (data_get($doc, 'kategori') ?: 'Umum'));
                        $catSlug = Str::slug($kategoriLabel);
                        $isReady = (bool) data_get($doc, 'file_exists', false);
                    @endphp
                    <article class="akd__card" data-akd-item data-cat="{{ $catSlug }}"
                        data-title="{{ Str::lower((data_get($doc, 'jeneng') ?? '') . ' ' . (data_get($doc, 'katrangan') ?? '') . ' ' . $kategoriLabel) }}">

                        <div class="akd__card-top">
                            <div class="akd__doc-icon" aria-hidden="true">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.9" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                    <line x1="16" y1="13" x2="8" y2="13" />
                                    <line x1="16" y1="17" x2="8" y2="17" />
                                    <polyline points="10 9 9 9 8 9" />
                                </svg>
                                <span class="akd__doc-ext">{{ data_get($doc, 'file_ext', 'DOCX') }}</span>
                            </div>

                            <span class="akd__cat-badge">{{ $kategoriLabel }}</span>
                        </div>

                        <h3 class="akd__card-title">{{ data_get($doc, 'jeneng') }}</h3>

                        @if (data_get($doc, 'katrangan'))
                            <p class="akd__card-desc">{{ data_get($doc, 'katrangan') }}</p>
                        @endif

                        <div class="akd__card-foot">
                            <span class="akd__file-meta">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" />
                                    <polyline points="13 2 13 9 20 9" />
                                </svg>
                                Microsoft Word ({{ data_get($doc, 'file_size', 'DOCX') }})
                            </span>

                            @if ($isReady)
                                <a href="{{ data_get($doc, 'download_url') }}" class="akd__btn-dl">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="7 10 12 15 17 10" />
                                        <line x1="12" y1="15" x2="12" y2="3" />
                                    </svg>
                                    <span>Unduh Form (.{{ strtolower(data_get($doc, 'file_ext', 'docx')) }})</span>
                                </a>
                            @else
                                <span class="akd__btn-dl is-disabled" title="File belum ditemukan di server">
                                    Segera Tersedia
                                </span>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="akd__empty" id="akd-empty" hidden>
                <h3>Formulir tidak ditemukan</h3>
                <p>Coba gunakan kata kunci pencarian lain atau pilih kategori <strong>Semua Formulir</strong>.</p>
            </div>
        @else
            <div class="akd__empty">
                <h3>Belum ada formulir akademik</h3>
                <p>Daftar dokumen formulir akan tampil setelah diunggah melalui panel admin.</p>
            </div>
        @endif

        {{-- Alur Pengajuan Administrasi Akademik --}}
        <div class="akd__steps rv">
            <h3 class="akd__steps-title">Alur Pengajuan Surat &amp; Formulir</h3>
            <div class="akd__steps-grid">
                <div class="akd__step">
                    <span class="akd__step-num">01</span>
                    <div>
                        <strong>Unduh &amp; Isi Formulir</strong>
                        <p>Pilih formulir <strong>.docx</strong> sesuai keperluan, lalu lengkapi biodata NIM, Program
                            Studi, dan keperluan surat.</p>
                    </div>
                </div>
                <div class="akd__step">
                    <span class="akd__step-num">02</span>
                    <div>
                        <strong>Validasi Dosen PA / Kaprodi</strong>
                        <p>Mintakan tanda tangan persetujuan Dosen Pembimbing Akademik atau Ketua Program Studi (jika
                            dipersyaratkan).</p>
                    </div>
                </div>
                <div class="akd__step">
                    <span class="akd__step-num">03</span>
                    <div>
                        <strong>Serahkan ke Loket BAAK</strong>
                        <p>Serahkan berkas ke Biro Administrasi Akademik untuk penerbitan surat resmi (estimasi 1–2 hari
                            kerja).</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA Bantuan --}}
        <div class="akd__cta rv">
            <div>
                <h3>Membutuhkan surat keterangan khusus lainnya?</h3>
                <p>Hubungi staf Biro Administrasi Akademik (BAAK) untuk konsultasi persyaratan dan format pengajuan.</p>
            </div>
            <a class="btn" href="https://wa.me/6281117014000" target="_blank" rel="noopener noreferrer">
                Hubungi Akademik &rarr;
            </a>
        </div>

    </div>
</section>

@push('js')
    <script>
        (function() {
            if (window.__akdFilterBound) return;
            window.__akdFilterBound = true;

            var activeCat = 'all';
            var keyword = '';

            function renderAkdCards() {
                var grid = document.getElementById('akd-grid');
                if (!grid) return;

                grid.classList.add('in');
                grid.style.opacity = '1';

                var cards = grid.querySelectorAll('[data-akd-item]');
                var empty = document.getElementById('akd-empty');
                var shown = 0;

                for (var i = 0; i < cards.length; i++) {
                    var card = cards[i];
                    var cardCat = (card.getAttribute('data-cat') || '').trim().toLowerCase();
                    var cardTitle = (card.getAttribute('data-title') || '').toLowerCase();

                    var matchCat = (activeCat === 'all') || (cardCat === activeCat);
                    var matchText = (!keyword) || (cardTitle.indexOf(keyword) !== -1);

                    if (matchCat && matchText) {
                        card.removeAttribute('hidden');
                        card.style.display = 'flex';
                        card.style.opacity = '1';
                        card.style.visibility = 'visible';
                        shown++;
                    } else {
                        card.setAttribute('hidden', 'hidden');
                        card.style.display = 'none';
                    }
                }

                if (empty) {
                    if (shown > 0) {
                        empty.setAttribute('hidden', 'hidden');
                        empty.style.display = 'none';
                    } else {
                        empty.removeAttribute('hidden');
                        empty.style.display = 'block';
                    }
                }
            }

            document.addEventListener('click', function(e) {
                var el = e.target && e.target.nodeType === 3 ? e.target.parentElement : e.target;
                if (!el || !el.closest) return;

                var btn = el.closest('[data-akd-cat]');
                if (!btn) return;

                e.preventDefault();
                activeCat = (btn.getAttribute('data-akd-cat') || 'all').trim().toLowerCase();

                var tabs = document.querySelectorAll('[data-akd-cat]');
                for (var i = 0; i < tabs.length; i++) {
                    var isOn = tabs[i] === btn;
                    tabs[i].classList.toggle('is-active', isOn);
                    tabs[i].setAttribute('aria-selected', isOn ? 'true' : 'false');
                }

                renderAkdCards();
            });

            document.addEventListener('input', function(e) {
                if (e.target && e.target.id === 'akd-search-input') {
                    keyword = e.target.value.trim().toLowerCase();
                    renderAkdCards();
                }
            });
        })();
    </script>
@endpush
