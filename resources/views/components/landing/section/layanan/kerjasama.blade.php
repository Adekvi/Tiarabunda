@props([
    'mitra' => [],
    'penawaran' => [],
    'kategoriList' => [],
    'selected' => null,
    'stats' => [],
    'basePath' => 'landing/assets/img/webp',
])

@php
    $mitraList = $mitra instanceof \Illuminate\Support\Collection ? $mitra : collect($mitra);
    $penawaranList = $penawaran instanceof \Illuminate\Support\Collection ? $penawaran : collect($penawaran);
    $kategoriItems = is_array($kategoriList) ? $kategoriList : [];

    $allDbItems = $mitraList->concat($penawaranList)->values();

    // Daftar logo mitra bawaan (landing/assets/img/webp)
    $defaultUniversitas = [
        'ui.webp' => 'Universitas Indonesia (UI)',
        'unpad.webp' => 'Universitas Padjadjaran (UNPAD)',
        'gunadharma.webp' => 'Universitas Gunadarma',
        'unpancasila.webp' => 'Universitas Pancasila',
        'unjani.webp' => 'Universitas Jenderal Achmad Yani (UNJANI)',
        'unpak.webp' => 'Universitas Pakuan (UNPAK)',
        'unbhakti.webp' => 'Universitas Bhakti Kencana',
        'DONGSEO.webp' => 'Dongseo University',
        'Fooyin.webp' => 'Fooyin University',
        'NTUNHS.webp' => 'National Taipei University of Nursing & Health Sciences',
        'inti.webp' => 'INTI International University',
        'lincoln.webp' => 'Lincoln University College',
        'uis.webp' => 'Universiti Islam Selangor (UIS)',
        'stikesassyifa.webp' => 'STIKes Assyifa',
        'stikesnh.webp' => 'STIKes NH',
        'untedc.webp' => 'Politeknik TEDC',
    ];

    $defaultLainnya = [
        'rsannisa.webp' => 'RS Annisa',
        'rsbrimob.webp' => 'RS Bhayangkara Brimob',
        'rspmibogor.webp' => 'RS PMI Bogor',
        'rssentosa.webp' => 'RS Sentosa',
        'rssitanala.webp' => 'RSUP Dr. Sitanala',
        'rstuguibu.webp' => 'RSU Tugu Ibu Depok',
        'rsutangsel.webp' => 'RSU Kota Tangerang Selatan',
        'klinikrizka.webp' => 'Klinik Rizka',
        'kimiafarma.webp' => 'PT Kimia Farma',
        'viaapotek.webp' => 'Via Apotek',
        'dinkesdepok.webp' => 'Dinas Kesehatan Kota Depok',
        'dinkesmalteng.webp' => 'Dinas Kesehatan Maluku Tengah',
        'bnndepok.webp' => 'BNN Kota Depok',
        'pemkabsukabumi.webp' => 'Pemerintah Kabupaten Sukabumi',
        'pemkotbekasi.webp' => 'Pemerintah Kota Bekasi',
        'kanwil_kumham_jabar.webp' => 'Kanwil Kemenkumham Jawa Barat',
        'pmibogor.webp' => 'PMI Kota Bogor',
        'ibibekasi.webp' => 'Ikatan Bidan Indonesia (IBI) Bekasi',
        'bidandelima.webp' => 'Bidan Delima',
        'kpri.webp' => 'KPRI',
        'ptavin.webp' => 'PT Avin',
        'ptfast.webp' => 'PT Fast',
        'aprat.webp' => 'Aprat',
        'ara.webp' => 'ARA',
        'mrm.webp' => 'MRM',
        'mvp.webp' => 'MVP',
        'sali.webp' => 'SALI',
    ];

    $prefix = trim($basePath, '/');
    $logoGallery = [];

    // 1) Logo mitra dari Superadmin (tampil paling awal)
    foreach ($mitraList as $dbMitra) {
        if (!empty($dbMitra->logo_url)) {
            $isUniv = \Illuminate\Support\Str::contains(strtolower((string) ($dbMitra->kategori ?? '')), [
                'universitas',
                'kampus',
                'pendidikan',
            ]);
            $logoGallery[] = [
                'src' => $dbMitra->logo_url,
                'nama' => $dbMitra->nama,
                'kelompok' => $isUniv ? 'universitas' : 'industri',
                'label' => $dbMitra->kategori ?: ($isUniv ? 'Universitas' : 'Instansi & Industri'),
                'url' => $dbMitra->detail_url ?? null,
            ];
        }
    }

    // 2) Logo bawaan Universitas
    foreach ($defaultUniversitas as $file => $label) {
        $logoGallery[] = [
            'src' => asset($prefix . '/' . ltrim($file, '/')),
            'nama' => $label,
            'kelompok' => 'universitas',
            'label' => 'Universitas',
            'url' => null,
        ];
    }

    // 3) Logo bawaan Kesehatan, Instansi & Industri
    foreach ($defaultLainnya as $file => $label) {
        $logoGallery[] = [
            'src' => asset($prefix . '/' . ltrim($file, '/')),
            'nama' => $label,
            'kelompok' => 'industri',
            'label' => 'Kesehatan, Instansi & Industri',
            'url' => null,
        ];
    }

    $logoUnivList = collect($logoGallery)->where('kelompok', 'universitas')->values()->all();
    $logoIndustriList = collect($logoGallery)->where('kelompok', 'industri')->values()->all();

    $totalSemuaMitra = max((int) ($stats['mitra'] ?? 0), count($logoGallery));
    $totalPenawaran = (int) ($stats['penawaran'] ?? $penawaranList->count());
    $totalKategori = max((int) ($stats['kategori'] ?? count($kategoriItems)), 4);

    $kontakCfg = config('landing.site.kontak', []);
    $rawPhone = (string) ($kontakCfg['telp'] ?? config('landing.site.hotline', '0811-1701-4000'));
    $phoneDigits = preg_replace('/[^0-9]/', '', $rawPhone);
    if (str_starts_with($phoneDigits, '0')) {
        $waNumber = '62' . substr($phoneDigits, 1);
    } elseif ($phoneDigits !== '') {
        $waNumber = $phoneDigits;
    } else {
        $waNumber = '6281117014000';
    }
    $waUrl = (string) ($kontakCfg['wa'] ?? 'https://wa.me/' . $waNumber);
    $emailKontak = (string) ($kontakCfg['email'] ?? '');
    $namaKampus = (string) config('landing.site.nama', 'Universitas Tiara Bunda');
@endphp

<section class="sec page-body kerjasama-sec" id="kerjasama-section" data-ks-root>
    <div class="ks-bg-glow ks-bg-glow--navy" aria-hidden="true"></div>
    <div class="ks-bg-glow ks-bg-glow--gold" aria-hidden="true"></div>

    <div class="wrap ks-wrap">
        @if ($selected)
            @php
                $relatedList = $allDbItems
                    ->reject(fn($row) => ($row->tandha ?? '') === ($selected->tandha ?? ''))
                    ->sortByDesc(function ($row) use ($selected) {
                        $score = 0;
                        if (($row->kategori ?? '') === ($selected->kategori ?? '')) {
                            $score += 2;
                        }
                        if (($row->jenis ?? '') === ($selected->jenis ?? '')) {
                            $score += 1;
                        }
                        return $score;
                    })
                    ->take(3)
                    ->values();
            @endphp

            {{-- ==================== TAMPILAN DETAIL KERJASAMA ==================== --}}
            <div class="ks-detail-wrap rv">
                <div class="ks-breadcrumb">
                    <a href="{{ url('/layanan/kerjasama') }}" class="ks-back-link">
                        <span aria-hidden="true">&larr;</span>
                        <span>Kembali ke Pusat Kerjasama</span>
                    </a>
                </div>

                <article class="ks-detail-card">
                    <div class="ks-detail-accent" aria-hidden="true"></div>

                    <div class="ks-detail-head">
                        <div class="ks-detail-logo">
                            @if (!empty($selected->logo_url))
                                <img src="{{ $selected->logo_url }}" alt="Logo {{ $selected->nama }}" loading="lazy">
                            @else
                                <span class="ks-logo-fallback">
                                    {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($selected->nama, 0, 2)) }}
                                </span>
                            @endif
                        </div>

                        <div class="ks-detail-meta">
                            <div class="ks-badges">
                                <span class="ks-badge ks-badge--gold">{{ $selected->jenis_label }}</span>
                                <span class="ks-badge ks-badge--navy">{{ $selected->kategori }}</span>
                            </div>
                            <h1 class="ks-detail-title">{{ $selected->nama }}</h1>

                            <div class="ks-meta-grid">
                                @if (!empty($selected->nomor_dokumen))
                                    <div class="ks-meta-box">
                                        <small>Nomor Dokumen</small>
                                        <strong>{{ $selected->nomor_dokumen }}</strong>
                                    </div>
                                @endif
                                @if (!empty($selected->periode))
                                    <div class="ks-meta-box">
                                        <small>Periode Berlaku</small>
                                        <strong>{{ $selected->periode }}</strong>
                                    </div>
                                @endif
                                @if (!empty($selected->kontak_pic))
                                    <div class="ks-meta-box">
                                        <small>Narahubung / PIC</small>
                                        <strong>{{ $selected->kontak_pic }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if (!empty($selected->keterangan))
                        <div class="ks-detail-body prose">
                            <h3>Ruang Lingkup &amp; Keterangan</h3>
                            <div class="ks-rich-text">{!! nl2br(e($selected->keterangan)) !!}</div>
                        </div>
                    @endif

                    <div class="ks-detail-actions">
                        @if (!empty($selected->has_file) && !empty($selected->file_url))
                            <a href="{{ $selected->file_url }}" target="_blank" rel="noopener noreferrer"
                                class="ks-btn ks-btn--gold">
                                <span>Unduh Dokumen ({{ $selected->file_ext }} · {{ $selected->file_size }})</span>
                                <span aria-hidden="true">&#8595;</span>
                            </a>
                        @endif

                        @if (!empty($selected->tautan))
                            <a href="{{ $selected->tautan }}" target="_blank" rel="noopener noreferrer"
                                class="ks-btn ks-btn--navy-outline">
                                <span>Kunjungi Tautan Resmi</span>
                                <span aria-hidden="true">&#8599;</span>
                            </a>
                        @endif

                        <a href="#kontak-kerjasama" class="ks-btn ks-btn--ghost">
                            <span>Hubungi Tim Kerjasama</span>
                        </a>
                    </div>
                </article>

                @if ($relatedList->isNotEmpty())
                    <div class="ks-block ks-block--compact">
                        <div class="ks-block-head">
                            <div>
                                <span class="ks-kicker">Rekomendasi</span>
                                <h2>Kerjasama Terkait</h2>
                            </div>
                        </div>
                        <div class="ks-grid">
                            @foreach ($relatedList as $rel)
                                <article class="ks-card">
                                    <div class="ks-card-top">
                                        <div class="ks-card-logo">
                                            @if (!empty($rel->logo_url))
                                                <img src="{{ $rel->logo_url }}" alt="Logo {{ $rel->nama }}"
                                                    loading="lazy">
                                            @else
                                                <span class="ks-logo-fallback">
                                                    {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($rel->nama, 0, 2)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="ks-badge ks-badge--navy">{{ $rel->kategori }}</span>
                                    </div>
                                    <h3 class="ks-card-title">
                                        <a href="{{ $rel->detail_url }}">{{ $rel->nama }}</a>
                                    </h3>
                                    @if (!empty($rel->keterangan))
                                        <p class="ks-card-desc">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($rel->keterangan), 115) }}
                                        </p>
                                    @endif
                                    <div class="ks-card-foot">
                                        <a href="{{ $rel->detail_url }}" class="ks-link-detail">Lihat Detail &rarr;</a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            {{-- ==================== BANNER RINGKAS & METRIK ==================== --}}
            <div class="ks-hero-banner rv">
                <div class="ks-hero-content">
                    <span class="ks-kicker ks-kicker--light">Kemitraan &amp; Kolaborasi Strategis</span>
                    <h2>Terbuka untuk Diajak Kerjasama Bersama {{ $namaKampus }}</h2>
                    <p>
                        Kolaborasi pendidikan vokasi, magang mahasiswa, praktik klinik, riset terapan, dan
                        pengabdian masyarakat bersama Universitas, rumah sakit, instansi pemerintah, dan industri.
                    </p>
                    <div class="ks-hero-cta">
                        <a href="#penawaran-kerjasama" class="ks-btn ks-btn--gold">
                            <span>Tawaran Kerjasama</span>
                            <span aria-hidden="true">&darr;</span>
                        </a>
                        <a href="#kontak-kerjasama" class="ks-btn ks-btn--glass">
                            <span>Hubungi ({{ $rawPhone }})</span>
                        </a>
                    </div>
                </div>

                <div class="ks-stats">
                    <div class="ks-stat-card">
                        <strong>{{ $totalSemuaMitra }}+</strong>
                        <span>Mitra Institusi</span>
                    </div>
                    <div class="ks-stat-card">
                        <strong>{{ $totalKategori }}</strong>
                        <span>Bidang Kemitraan</span>
                    </div>
                    <div class="ks-stat-card ks-stat-card--accent">
                        <strong>{{ $totalPenawaran > 0 ? $totalPenawaran : 'Terbuka' }}</strong>
                        <span>{{ $totalPenawaran > 0 ? 'Penawaran Aktif' : 'Peluang MoU' }}</span>
                    </div>
                </div>
            </div>

            {{-- ==================== SHOWCASE LOGO MITRA (1 BARIS RAMPING) ==================== --}}
            @if (count($logoGallery) > 0)
                <div class="ks-block ks-block--logos rv" id="galeri-mitra">
                    <div class="ks-block-head ks-block-head--tight">
                        <div>
                            <span class="ks-kicker">Dipercaya Berbagai Institusi</span>
                            <h2>Jejaring Mitra Institusi &amp; Industri</h2>
                        </div>

                        <button type="button" class="ks-btn ks-btn--navy-outline ks-btn--sm" data-ks-dir-open>
                            <span>Lihat Semua Mitra ({{ count($logoGallery) }})</span>
                            <span aria-hidden="true">&#8599;</span>
                        </button>
                    </div>

                    <div class="ks-marquee-shell">
                        <div class="ks-marquee-viewport" aria-label="Daftar logo mitra kerja sama">
                            <div class="ks-marquee-track ks-marquee-track--left">
                                @for ($pass = 0; $pass < 2; $pass++)
                                    <div class="ks-marquee-group"
                                        @if ($pass === 1) aria-hidden="true" @endif>
                                        @foreach ($logoGallery as $logo)
                                            <div class="ks-marquee-pill"
                                                title="{{ $logo['nama'] }} — {{ $logo['label'] }}">
                                                <img src="{{ $logo['src'] }}"
                                                    alt="{{ $pass === 0 ? 'Logo ' . $logo['nama'] : '' }}"
                                                    width="112" height="42" loading="lazy" decoding="async"
                                                    onerror="this.closest('.ks-marquee-pill').style.display='none'">
                                            </div>
                                        @endforeach
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ==================== DOKUMEN & INFORMASI KERJASAMA (PAGINATED / LOAD MORE) ==================== --}}
            @if ($allDbItems->isNotEmpty())
                <div class="ks-block rv" id="daftar-mitra" data-ks-paginated data-ks-step="6">
                    <div class="ks-block-head">
                        <div>
                            <span class="ks-kicker">Arsip &amp; Profil Kemitraan</span>
                            <h2>Dokumen &amp; Informasi Kerjasama Terkait</h2>
                        </div>

                        <div class="ks-controls">
                            <input type="search" class="ks-search-input"
                                placeholder="Cari dokumen, nomor MoU, atau bidang..."
                                aria-label="Cari dokumen kerjasama" data-ks-search>
                        </div>
                    </div>

                    @if (count($kategoriItems) > 1 || $penawaranList->isNotEmpty())
                        <div class="ks-filter-bar" aria-label="Filter kategori dan jenis kerjasama">
                            <button type="button" class="ks-filter-btn is-active" data-ks-filter-type="all"
                                data-ks-filter-cat="all">
                                Semua ({{ $allDbItems->count() }})
                            </button>
                            <button type="button" class="ks-filter-btn" data-ks-filter-type="mitra"
                                data-ks-filter-cat="all">
                                Mitra Instansi ({{ $mitraList->count() }})
                            </button>
                            <button type="button" class="ks-filter-btn" data-ks-filter-type="penawaran"
                                data-ks-filter-cat="all">
                                Penawaran Kerjasama ({{ $penawaranList->count() }})
                            </button>
                            @foreach ($kategoriItems as $kat)
                                <button type="button" class="ks-filter-btn ks-filter-btn--cat"
                                    data-ks-filter-type="all" data-ks-filter-cat="{{ $kat }}">
                                    {{ $kat }}
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <div class="ks-grid" data-ks-grid>
                        @foreach ($allDbItems as $idx => $item)
                            @php
                                $searchBlob = strtolower(
                                    ($item->nama ?? '') .
                                        ' ' .
                                        ($item->kategori ?? '') .
                                        ' ' .
                                        ($item->nomor_dokumen ?? '') .
                                        ' ' .
                                        ($item->periode ?? '') .
                                        ' ' .
                                        ($item->kontak_pic ?? '') .
                                        ' ' .
                                        strip_tags($item->keterangan ?? ''),
                                );
                                $payload = [
                                    'nama' => $item->nama,
                                    'jenis_label' => $item->jenis_label,
                                    'kategori' => $item->kategori,
                                    'nomor_dokumen' => $item->nomor_dokumen,
                                    'periode' => $item->periode,
                                    'kontak_pic' => $item->kontak_pic,
                                    'keterangan' => $item->keterangan,
                                    'logo_url' => $item->logo_url,
                                    'has_file' => (bool) ($item->has_file ?? false),
                                    'file_url' => $item->file_url,
                                    'file_ext' => $item->file_ext,
                                    'file_size' => $item->file_size,
                                    'tautan' => $item->tautan,
                                    'detail_url' => $item->detail_url,
                                ];
                            @endphp
                            <article
                                class="ks-card {{ ($item->jenis ?? '') === 'penawaran' ? 'ks-card--offer' : '' }}"
                                data-ks-item data-jenis="{{ $item->jenis ?? 'mitra' }}"
                                data-kategori="{{ $item->kategori ?? '' }}" data-search="{{ $searchBlob }}"
                                @if ($idx >= 6) hidden @endif>
                                <div class="ks-card-top">
                                    <div class="ks-card-logo">
                                        @if (!empty($item->logo_url))
                                            <img src="{{ $item->logo_url }}" alt="Logo {{ $item->nama }}"
                                                loading="lazy">
                                        @else
                                            <span class="ks-logo-fallback">
                                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($item->nama, 0, 2)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="ks-card-tags">
                                        <span
                                            class="ks-badge {{ ($item->jenis ?? '') === 'penawaran' ? 'ks-badge--gold' : 'ks-badge--navy' }}">
                                            {{ ($item->jenis ?? '') === 'penawaran' ? 'Penawaran' : 'Mitra' }}
                                        </span>
                                        <span class="ks-badge ks-badge--soft">{{ $item->kategori }}</span>
                                    </div>
                                </div>

                                <h3 class="ks-card-title">
                                    <a href="{{ $item->detail_url }}">{{ $item->nama }}</a>
                                </h3>

                                @if (!empty($item->keterangan))
                                    <p class="ks-card-desc">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->keterangan), 120) }}
                                    </p>
                                @endif

                                <div class="ks-card-meta">
                                    @if (!empty($item->nomor_dokumen))
                                        <span>Dokumen: <strong>{{ $item->nomor_dokumen }}</strong></span>
                                    @endif
                                    @if (!empty($item->periode))
                                        <span>Periode: <strong>{{ $item->periode }}</strong></span>
                                    @endif
                                    @if (!empty($item->kontak_pic))
                                        <span>PIC: <strong>{{ $item->kontak_pic }}</strong></span>
                                    @endif
                                </div>

                                <div class="ks-card-foot">
                                    <div class="ks-card-primary-actions">
                                        <button type="button" class="ks-btn-quick"
                                            data-ks-preview='@json($payload)'>
                                            Pratinjau
                                        </button>
                                        <a href="{{ $item->detail_url }}" class="ks-link-detail">Detail &rarr;</a>
                                    </div>

                                    <div class="ks-card-links">
                                        @if (!empty($item->has_file) && !empty($item->file_url))
                                            <a href="{{ $item->file_url }}" target="_blank"
                                                rel="noopener noreferrer"
                                                title="Unduh Dokumen ({{ $item->file_ext }})">
                                                {{ $item->file_ext }}
                                            </a>
                                        @endif
                                        @if (!empty($item->tautan))
                                            <a href="{{ $item->tautan }}" target="_blank" rel="noopener noreferrer">
                                                Web &#8599;
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Bar Kontrol Muat Lebih Banyak / Ringkas --}}
                    <div class="ks-load-bar" data-ks-load-bar @if ($allDbItems->count() <= 6) hidden @endif>
                        <span class="ks-load-info" data-ks-load-info>
                            Menampilkan {{ min(6, $allDbItems->count()) }} dari {{ $allDbItems->count() }} data
                            kerjasama
                        </span>
                        <div class="ks-load-btns">
                            <button type="button" class="ks-btn ks-btn--navy-outline" data-ks-load-more>
                                Tampilkan Lebih Banyak
                            </button>
                            <button type="button" class="ks-btn ks-btn--ghost" data-ks-load-less hidden>
                                Ringkas
                            </button>
                        </div>
                    </div>

                    <div class="ks-empty ks-empty--filtered" data-ks-no-result hidden>
                        <h3>Tidak ada data yang cocok dengan filter</h3>
                        <p>Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
                        <div class="ks-empty-actions">
                            <button type="button" class="ks-btn ks-btn--navy-outline" data-ks-reset>
                                Reset Filter
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ==================== TAWARAN / TERBUKA UNTUK KERJASAMA (RINGKAS) ==================== --}}
            <div class="ks-block ks-block--opportunity rv" id="penawaran-kerjasama">
                <div class="ks-block-head">
                    <div>
                        <span class="ks-kicker">Peluang Kolaborasi</span>
                        <h2>Tawaran &amp; Bidang Terbuka untuk Kerjasama</h2>
                    </div>

                    @if ($penawaranList->count() > 3)
                        <a href="#daftar-mitra" class="ks-btn ks-btn--navy-outline" data-ks-jump-penawaran>
                            Lihat Semua Penawaran ({{ $penawaranList->count() }}) &uarr;
                        </a>
                    @endif
                </div>

                @if ($penawaranList->isNotEmpty())
                    <div class="ks-offer-grid">
                        @foreach ($penawaranList->take(3) as $offer)
                            <article class="ks-offer-card">
                                <div class="ks-offer-head">
                                    <span class="ks-badge ks-badge--gold">{{ $offer->kategori }}</span>
                                    @if (!empty($offer->periode))
                                        <span class="ks-offer-period">{{ $offer->periode }}</span>
                                    @endif
                                </div>

                                <h3>
                                    <a href="{{ $offer->detail_url }}">{{ $offer->nama }}</a>
                                </h3>

                                @if (!empty($offer->keterangan))
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($offer->keterangan), 150) }}</p>
                                @endif

                                @if (!empty($offer->kontak_pic))
                                    <p class="ks-offer-pic">
                                        PIC: <strong>{{ $offer->kontak_pic }}</strong>
                                    </p>
                                @endif

                                <div class="ks-offer-actions">
                                    <a href="{{ $offer->detail_url }}" class="ks-btn ks-btn--navy">Detail</a>
                                    <a href="{{ $waUrl }}?text={{ rawurlencode('Halo ' . $namaKampus . ', kami tertarik membahas penawaran kerjasama: ' . $offer->nama) }}"
                                        target="_blank" rel="noopener noreferrer" class="ks-btn ks-btn--gold">
                                        Ajukan via WA &#8599;
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="ks-open-tracks">
                        <article class="ks-track-card">
                            <span class="ks-track-num">01</span>
                            <h3>Magang &amp; Penyerapan Lulusan</h3>
                            <p>Penempatan mahasiswa vokasi &amp; sarjana terapan, praktik industri, serta rekrutmen
                                tenaga kerja.</p>
                        </article>
                        <article class="ks-track-card">
                            <span class="ks-track-num">02</span>
                            <h3>Layanan Kesehatan &amp; Praktik Klinik</h3>
                            <p>Kolaborasi rumah sakit, puskesmas, laboratorium, dan kegiatan promotif-preventif
                                kesehatan.</p>
                        </article>
                        <article class="ks-track-card">
                            <span class="ks-track-num">03</span>
                            <h3>Riset, Pengabdian &amp; MoU Instansi</h3>
                            <p>Penelitian bersama, pelatihan SDM, seminar/workshop, dan perjanjian kerja sama
                                kelembagaan.</p>
                        </article>
                    </div>
                @endif
            </div>
        @endif

        {{-- ==================== NOMOR & KONTAK YANG DAPAT DIHUBUNGI ==================== --}}
        <div class="ks-contact-panel rv" id="kontak-kerjasama">
            <div class="ks-contact-info">
                <span class="ks-kicker ks-kicker--light">Layanan Kemitraan Resmi</span>
                <h3>Nomor &amp; Kanal Kontak Kerjasama</h3>
                <p>
                    Instansi, perusahaan, atau calon mitra yang ingin berdiskusi maupun mengajukan kerja sama dapat
                    menghubungi nomor resmi {{ $namaKampus }} di bawah ini.
                </p>
            </div>

            <div class="ks-contact-cards">
                <div class="ks-contact-box">
                    <small>Nomor Telepon / Hotline</small>
                    <strong data-ks-phone-value>{{ $rawPhone }}</strong>
                    <div class="ks-contact-btns">
                        <a href="tel:{{ $phoneDigits ?: '081117014000' }}" class="ks-mini-link">
                            Telepon &#8599;
                        </a>
                        <button type="button" class="ks-mini-copy" data-ks-copy="{{ $rawPhone }}">
                            Salin Nomor
                        </button>
                    </div>
                </div>

                <div class="ks-contact-box ks-contact-box--gold">
                    <small>WhatsApp Resmi Kemitraan</small>
                    <strong>{{ $rawPhone }}</strong>
                    <div class="ks-contact-btns">
                        <a href="{{ $waUrl }}?text={{ rawurlencode('Halo ' . $namaKampus . ', saya ingin berkonsultasi mengenai peluang kerjasama.') }}"
                            target="_blank" rel="noopener noreferrer" class="ks-mini-link ks-mini-link--dark">
                            Chat WhatsApp &#8599;
                        </a>
                    </div>
                </div>

                @if ($emailKontak !== '')
                    <div class="ks-contact-box">
                        <small>Email / Portal Resmi</small>
                        <strong class="ks-contact-email">{{ $emailKontak }}</strong>
                        <div class="ks-contact-btns">
                            @if (\Illuminate\Support\Str::contains($emailKontak, '@'))
                                <a href="mailto:{{ $emailKontak }}" class="ks-mini-link">Kirim Email &#8599;</a>
                            @else
                                <a href="{{ \Illuminate\Support\Str::startsWith($emailKontak, 'http') ? $emailKontak : 'https://' . $emailKontak }}"
                                    target="_blank" rel="noopener noreferrer" class="ks-mini-link">
                                    Buka Situs &#8599;
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ==================== MODAL DIREKTORI SEMUA MITRA ==================== --}}
    @if (!$selected && count($logoGallery) > 0)
        <div class="ks-modal" data-ks-dir-modal hidden aria-hidden="true" role="dialog" aria-modal="true"
            aria-labelledby="ksDirModalTitle">
            <div class="ks-modal-backdrop" data-ks-dir-close></div>
            <div class="ks-modal-dialog ks-modal-dialog--wide">
                <div class="ks-modal-head">
                    <div>
                        <span class="ks-kicker">Direktori Lengkap</span>
                        <h3 id="ksDirModalTitle">Seluruh Mitra Institusi ({{ count($logoGallery) }})</h3>
                    </div>
                    <button type="button" class="ks-modal-close" aria-label="Tutup direktori" data-ks-dir-close>
                        &times;
                    </button>
                </div>

                <div class="ks-dir-toolbar">
                    <input type="search" class="ks-search-input"
                        placeholder="Cari nama universitas, RS, atau instansi..."
                        aria-label="Cari mitra dalam direktori" data-ks-dir-search>
                    <div class="ks-dir-pills">
                        <button type="button" class="ks-filter-btn is-active" data-ks-dir-filter="all">
                            Semua ({{ count($logoGallery) }})
                        </button>
                        <button type="button" class="ks-filter-btn" data-ks-dir-filter="universitas">
                            Universitas ({{ count($logoUnivList) }})
                        </button>
                        <button type="button" class="ks-filter-btn" data-ks-dir-filter="industri">
                            Kesehatan &amp; Industri ({{ count($logoIndustriList) }})
                        </button>
                    </div>
                </div>

                <div class="ks-dir-scroll">
                    <div class="ks-logo-grid">
                        @foreach ($logoGallery as $logo)
                            @php $tag = !empty($logo['url']) ? 'a' : 'div'; @endphp
                            <{{ $tag }} @if (!empty($logo['url'])) href="{{ $logo['url'] }}" @endif
                                class="ks-logo-card" data-ks-dir-item data-kelompok="{{ $logo['kelompok'] }}"
                                data-search="{{ strtolower($logo['nama'] . ' ' . $logo['label']) }}">
                                <div class="ks-logo-img-wrap">
                                    <img src="{{ $logo['src'] }}" alt="Logo {{ $logo['nama'] }}" width="120"
                                        height="48" loading="lazy" decoding="async"
                                        onerror="this.closest('.ks-logo-card').style.display='none'">
                                </div>
                                <span class="ks-logo-name">{{ $logo['nama'] }}</span>
                                <span class="ks-logo-tag">{{ $logo['label'] }}</span>
                                </{{ $tag }}>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ==================== MODAL PRATINJAU CEPAT DOKUMEN ==================== --}}
    <div class="ks-modal" data-ks-modal hidden aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="ksModalTitle">
        <div class="ks-modal-backdrop" data-ks-modal-close></div>
        <div class="ks-modal-dialog">
            <div class="ks-modal-head">
                <div>
                    <div class="ks-badges" data-ks-modal-badges></div>
                    <h3 id="ksModalTitle" data-ks-modal-title></h3>
                </div>
                <button type="button" class="ks-modal-close" aria-label="Tutup pratinjau" data-ks-modal-close>
                    &times;
                </button>
            </div>
            <div class="ks-modal-body">
                <div class="ks-modal-meta" data-ks-modal-meta></div>
                <div class="ks-modal-desc" data-ks-modal-desc></div>
            </div>
            <div class="ks-modal-foot" data-ks-modal-foot></div>
        </div>
    </div>
</section>
