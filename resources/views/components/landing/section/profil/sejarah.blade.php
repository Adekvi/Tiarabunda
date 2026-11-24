@props([
    'item' => null,
    'site' => null,
    'fakultas' => null,
    'prodi' => null,
    'gambarMarket' => 'seed/fasilitas/fas5.webp',
    'keunggulan' => null,
    'deskripsiMarket' => null,
])

@php
    $site = $site ?? config('landing.site', []);
    $fakultas = $fakultas ?? config('landing.home.fakultas', []);
    $prodi = $prodi ?? config('landing.home.prodi', []);

    $judhul = data_get($item, 'judhul');
    $isi = data_get($item, 'isi', '');
    $kronologi = data_get($item, 'kronologi', []);

    if (is_string($kronologi)) {
        $kronologi = json_decode($kronologi, true) ?: [];
    }
    if ($kronologi instanceof \Illuminate\Support\Collection) {
        $kronologi = $kronologi->all();
    }
    if (!is_array($kronologi)) {
        $kronologi = [];
    }

    $sampul = data_get($item, 'path_sampul');
    $sampulUrl = $sampul
        ? (str_starts_with((string) $sampul, 'http')
            ? $sampul
            : asset('storage/' . ltrim((string) $sampul, '/')))
        : null;

    $paragraphs = [];
    $asHtml = false;
    if (is_string($isi) && $isi !== '') {
        if (str_contains($isi, '<p') || str_contains($isi, '<br')) {
            $asHtml = true;
        } else {
            $paragraphs = preg_split('/\n\s*\n/', trim($isi)) ?: [trim($isi)];
        }
    }

    $totalFakultas = is_countable($fakultas) ? count($fakultas) : 0;
    $totalProdi = is_countable($prodi) ? count($prodi) : 0;

    $gambarMarketUrl = $gambarMarket
        ? (str_starts_with((string) $gambarMarket, 'http')
            ? $gambarMarket
            : (str_starts_with((string) $gambarMarket, 'storage/') ||
            str_starts_with((string) $gambarMarket, 'landing/') ||
            str_starts_with((string) $gambarMarket, 'assets/')
                ? asset((string) $gambarMarket)
                : (file_exists(public_path('storage/' . ltrim((string) $gambarMarket, '/')))
                    ? asset('storage/' . ltrim((string) $gambarMarket, '/'))
                    : asset((string) $gambarMarket))))
        : null;

    $keunggulan = $keunggulan ?? [
        [
            'icon' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z" /><path d="M6 12v5c3 3 9 3 12 0v-5" />',
            'judul' => 'Kurikulum Berbasis Industri',
            'desc' => 'Materi ajar dirancang bersama mitra industri agar lulusan siap kerja sejak hari pertama.',
            'detail' =>
                'Kurikulum disusun bersama mitra industri dan diperbarui berkala mengikuti kebutuhan dunia kerja dan layanan kesehatan.',
        ],
        [
            'icon' =>
                '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />',
            'judul' => 'Dosen & Praktisi Berpengalaman',
            'desc' => 'Dibimbing langsung oleh akademisi dan praktisi aktif yang relevan dengan dunia kerja.',
            'detail' =>
                'Didukung tenaga pengajar akademisi dan praktisi lapangan di bidang kesehatan, pendidikan, teknologi, serta bisnis.',
        ],
        [
            'icon' =>
                '<rect x="2" y="7" width="20" height="14" rx="2" ry="2" /><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />',
            'judul' => 'Siap Kerja & Berdaya Saing',
            'desc' => 'Program praktik, sertifikasi kompetensi, dan pendampingan karier hingga lulus kuliah.',
            'detail' =>
                'Terhubung dengan jejaring mitra serta pendampingan karier untuk menyiapkan lulusan yang kompeten dan adaptif.',
        ],
    ];

    $deskripsiMarket =
        $deskripsiMarket ??
        sprintf(
            '%1$s hadir sebagai perguruan tinggi vokasi di %2$s yang berkomitmen mencetak lulusan terampil, inovatif, dan siap berkontribusi. Melalui pembelajaran praktik, kolaborasi mitra, serta pembinaan karakter, kami menyiapkan lulusan yang berdaya saing tinggi.',
            data_get($site, 'nama', 'Universitas Tiara Bunda'),
            data_get($site, 'kota', 'Kota Depok'),
        );
@endphp

<section class="sej" id="sejarah">
    <div class="wrap sej__wrap">
        {{-- 1. Sampul Sejarah --}}
        @if ($sampulUrl)
            <figure class="sej__cover rv">
                <img src="{{ $sampulUrl }}" alt="{{ $judhul ?: 'Sampul sejarah' }}" width="1200" height="520"
                    loading="eager" decoding="async">
                <figcaption class="sej__cover-shade" aria-hidden="true"></figcaption>
            </figure>
        @endif

        {{-- 2. Narasi Sejarah --}}
        <div class="sej__intro rv">
            @if ($judhul)
                <h2 class="sej__title">{{ $judhul }}</h2>
            @endif
            <div class="sej__body">
                @if ($asHtml)
                    {!! $isi !!}
                @else
                    @foreach ($paragraphs as $p)
                        <p>{{ $p }}</p>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- 3. Kronologi Perjalanan --}}
        @if (count($kronologi) > 0)
            <div class="sej__kron rv">
                <div class="sej__kron-head">
                    <p class="sej__eyebrow">Kronologi</p>
                    <h3>Perjalanan institusi</h3>
                </div>
                <ol class="sej__timeline">
                    @foreach ($kronologi as $i => $row)
                        @php
                            $tahun = data_get($row, 'tahun', data_get($row, 'k'));
                            $judulK = data_get($row, 'judhul', data_get($row, 't'));
                            $isiK = data_get($row, 'isi', data_get($row, 'd'));
                        @endphp
                        <li class="sej__item" style="--i: {{ $i }}">
                            <div class="sej__marker" aria-hidden="true">
                                <span>{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="sej__card">
                                @if ($tahun)
                                    <span class="sej__year">{{ $tahun }}</span>
                                @endif
                                @if ($judulK)
                                    <h4 class="sej__item-title">{{ $judulK }}</h4>
                                @endif
                                @if ($isiK)
                                    <p class="sej__item-text">{{ $isiK }}</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        @endif

        {{-- 4. Bagian Market Terintegrasi (Nilai, Statistik & Kepercayaan) --}}
        <div class="sej__mkt rv" id="tentang" data-market-section>
            <div class="sej__mkt-head">
                <p class="sej__eyebrow">Dampak &amp; Keunggulan</p>
                <h3>
                    Kampus yang Membawamu
                    <span>Lebih Dekat ke Dunia Kerja</span>
                </h3>
                <p class="sej__mkt-lead">{{ $deskripsiMarket }}</p>
            </div>

            <div class="sej__mkt-grid">
                <div class="sej__mkt-col">
                    <div class="sej__mkt-stats" data-counters>
                        @if ($totalFakultas > 0)
                            <div class="sej__mkt-stat">
                                <b class="sej__mkt-counter" data-target="{{ $totalFakultas }}" data-suffix="">0</b>
                                <span>Fakultas</span>
                            </div>
                        @endif
                        @if ($totalProdi > 0)
                            <div class="sej__mkt-stat">
                                <b class="sej__mkt-counter" data-target="{{ $totalProdi }}" data-suffix="">0</b>
                                <span>Program Studi</span>
                            </div>
                        @endif
                        <div class="sej__mkt-stat sej__mkt-stat--accent">
                            <b class="sej__mkt-counter" data-target="92" data-suffix="%">0%</b>
                            <span>Lulusan Terserap Kerja</span>
                        </div>
                    </div>

                    <ul class="sej__mkt-list" data-accordion>
                        @foreach ($keunggulan as $i => $k)
                            <li class="sej__mkt-item {{ $i === 0 ? 'is-open' : '' }}">
                                <button type="button" class="sej__mkt-item-head"
                                    aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                                    <span class="sej__mkt-ico" aria-hidden="true">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            {!! $k['icon'] !!}
                                        </svg>
                                    </span>
                                    <span class="sej__mkt-item-text">
                                        <span class="sej__mkt-item-top">
                                            <span class="sej__mkt-num">0{{ $i + 1 }}</span>
                                            <strong>{{ $k['judul'] }}</strong>
                                        </span>
                                        <small>{{ $k['desc'] }}</small>
                                    </span>
                                    <span class="sej__mkt-chevron" aria-hidden="true">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.4" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M6 9l6 6 6-6" />
                                        </svg>
                                    </span>
                                </button>
                                <div class="sej__mkt-item-body" @if ($i !== 0) hidden @endif>
                                    <p>{{ $k['detail'] ?? $k['desc'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if ($gambarMarketUrl)
                    <div class="sej__mkt-aside">
                        <figure class="sej__mkt-visual" data-visual>
                            <div class="sej__mkt-frame">
                                <img src="{{ $gambarMarketUrl }}"
                                    alt="Suasana kampus {{ data_get($site, 'nama', '') }}" loading="lazy"
                                    decoding="async" onerror="this.closest('.sej__mkt-aside').style.display='none'">
                                <div class="sej__mkt-shine"></div>
                            </div>
                            <figcaption class="sej__mkt-badge">
                                <span class="sej__mkt-dot"></span>
                                <span>Kampus Aktif &amp; Beroperasi</span>
                            </figcaption>
                        </figure>
                    </div>
                @endif
            </div>

            <div class="sej__mkt-trust">
                <div class="sej__mkt-trust-item">
                    <b>Akreditasi &amp; Legalitas</b>
                    <small>Terdaftar &amp; terakreditasi resmi</small>
                </div>
                <div class="sej__mkt-trust-item">
                    <b>Pembelajaran Vokasi</b>
                    <small>Fokus praktik &amp; kompetensi</small>
                </div>
                <div class="sej__mkt-trust-item">
                    <b>Jejaring Mitra</b>
                    <small>Kolaborasi industri &amp; layanan</small>
                </div>
                <div class="sej__mkt-trust-item">
                    <b>Pendampingan Karier</b>
                    <small>Siap masuk dunia kerja</small>
                </div>
            </div>
        </div>
    </div>
</section>
