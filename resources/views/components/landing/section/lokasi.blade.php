@props(['items' => []])

@php
    if ($items instanceof \Illuminate\Support\Collection) {
        $items = $items->all();
    }

    $svgKampusA =
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 560">' .
        '<defs>' .
        '<linearGradient id="skyA" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#dbeafe"/><stop offset="65%" stop-color="#eff6ff"/><stop offset="100%" stop-color="#fef3c7"/></linearGradient>' .
        '<linearGradient id="bldgA" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#173b66"/><stop offset="100%" stop-color="#0f2744"/></linearGradient>' .
        '<linearGradient id="goldA" x1="0" y1="0" x2="1" y2="0"><stop offset="0%" stop-color="#d4a24a"/><stop offset="50%" stop-color="#f3c46b"/><stop offset="100%" stop-color="#f2994a"/></linearGradient>' .
        '</defs>' .
        '<rect width="800" height="560" fill="url(#skyA)"/>' .
        '<circle cx="660" cy="110" r="54" fill="#fde68a" opacity="0.75"/>' .
        '<path d="M0 430 Q220 395 440 420 T800 405 L800 560 L0 560 Z" fill="#cbd5e1" opacity="0.45"/>' .
        '<rect x="0" y="455" width="800" height="105" fill="#e2e8f0"/>' .
        '<rect x="0" y="455" width="800" height="8" fill="#cbd5e1"/>' .
        '<rect x="115" y="220" width="155" height="235" rx="6" fill="#f8fafc" stroke="#cbd5e1" stroke-width="2"/>' .
        '<rect x="530" y="220" width="155" height="235" rx="6" fill="#f8fafc" stroke="#cbd5e1" stroke-width="2"/>' .
        '<rect x="110" y="212" width="165" height="12" rx="4" fill="#1e497a"/>' .
        '<rect x="525" y="212" width="165" height="12" rx="4" fill="#1e497a"/>' .
        '<g fill="#93c5fd" opacity="0.8">' .
        '<rect x="138" y="245" width="44" height="36" rx="4"/><rect x="202" y="245" width="44" height="36" rx="4"/>' .
        '<rect x="138" y="300" width="44" height="36" rx="4"/><rect x="202" y="300" width="44" height="36" rx="4"/>' .
        '<rect x="138" y="355" width="44" height="36" rx="4"/><rect x="202" y="355" width="44" height="36" rx="4"/>' .
        '<rect x="554" y="245" width="44" height="36" rx="4"/><rect x="618" y="245" width="44" height="36" rx="4"/>' .
        '<rect x="554" y="300" width="44" height="36" rx="4"/><rect x="618" y="300" width="44" height="36" rx="4"/>' .
        '<rect x="554" y="355" width="44" height="36" rx="4"/><rect x="618" y="355" width="44" height="36" rx="4"/>' .
        '</g>' .
        '<rect x="255" y="145" width="290" height="310" rx="8" fill="url(#bldgA)"/>' .
        '<polygon points="240,148 400,88 560,148" fill="#0f2744"/>' .
        '<polygon points="258,148 400,98 542,148" fill="url(#goldA)"/>' .
        '<rect x="255" y="152" width="290" height="6" fill="url(#goldA)"/>' .
        '<rect x="295" y="185" width="210" height="175" rx="6" fill="#1e497a" stroke="#60a5fa" stroke-width="1.5"/>' .
        '<g fill="#bae6fd" opacity="0.72">' .
        '<rect x="310" y="198" width="54" height="68" rx="3"/><rect x="373" y="198" width="54" height="68" rx="3"/><rect x="436" y="198" width="54" height="68" rx="3"/>' .
        '<rect x="310" y="278" width="54" height="68" rx="3"/><rect x="373" y="278" width="54" height="68" rx="3"/><rect x="436" y="278" width="54" height="68" rx="3"/>' .
        '</g>' .
        '<rect x="325" y="375" width="150" height="80" rx="4" fill="#0d223c"/>' .
        '<rect x="355" y="392" width="90" height="63" rx="3" fill="#fde68a" opacity="0.85"/>' .
        '<rect x="285" y="368" width="230" height="10" rx="3" fill="url(#goldA)"/>' .
        '<rect x="318" y="158" width="164" height="20" rx="10" fill="#0f2744" stroke="#f3c46b" stroke-width="1.2"/>' .
        '<text x="400" y="172" font-family="Arial, sans-serif" font-size="10.5" font-weight="bold" fill="#f6d58e" text-anchor="middle" letter-spacing="1.5">KAMPUS A • REKTORAT</text>' .
        '<g fill="#15803d"><circle cx="78" cy="405" r="36"/><circle cx="105" cy="418" r="28"/><circle cx="55" cy="422" r="24"/><circle cx="722" cy="405" r="36"/><circle cx="695" cy="418" r="28"/><circle cx="745" cy="422" r="24"/></g>' .
        '<rect x="74" y="425" width="8" height="30" fill="#78350f"/><rect x="718" y="425" width="8" height="30" fill="#78350f"/>' .
        '<path d="M295 455 L505 455 L545 560 L255 560 Z" fill="#cbd5e1"/>' .
        '</svg>';

    $svgKampusB =
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 560">' .
        '<defs>' .
        '<linearGradient id="skyB" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#e0f2fe"/><stop offset="70%" stop-color="#f0f9ff"/><stop offset="100%" stop-color="#fef9c3"/></linearGradient>' .
        '<linearGradient id="glassB" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#2f6db5"/><stop offset="100%" stop-color="#173b66"/></linearGradient>' .
        '</defs>' .
        '<rect width="800" height="560" fill="url(#skyB)"/>' .
        '<circle cx="150" cy="105" r="48" fill="#fde68a" opacity="0.7"/>' .
        '<rect x="0" y="455" width="800" height="105" fill="#e2e8f0"/>' .
        '<rect x="0" y="455" width="800" height="8" fill="#94a3b8"/>' .
        '<rect x="190" y="115" width="260" height="340" rx="10" fill="#ffffff" stroke="#cbd5e1" stroke-width="2"/>' .
        '<rect x="190" y="115" width="260" height="16" rx="6" fill="#173b66"/>' .
        '<rect x="212" y="148" width="216" height="225" rx="6" fill="url(#glassB)"/>' .
        '<g fill="#bae6fd" opacity="0.78">' .
        '<rect x="226" y="162" width="88" height="42" rx="4"/><rect x="326" y="162" width="88" height="42" rx="4"/>' .
        '<rect x="226" y="216" width="88" height="42" rx="4"/><rect x="326" y="216" width="88" height="42" rx="4"/>' .
        '<rect x="226" y="270" width="88" height="42" rx="4"/><rect x="326" y="270" width="88" height="42" rx="4"/>' .
        '<rect x="226" y="322" width="88" height="38" rx="4"/><rect x="326" y="322" width="88" height="38" rx="4"/>' .
        '</g>' .
        '<rect x="435" y="205" width="210" height="250" rx="8" fill="#173b66"/>' .
        '<rect x="435" y="205" width="210" height="6" fill="#f2994a"/>' .
        '<g fill="#93c5fd" opacity="0.82">' .
        '<rect x="462" y="232" width="68" height="45" rx="4"/><rect x="548" y="232" width="68" height="45" rx="4"/>' .
        '<rect x="462" y="294" width="68" height="45" rx="4"/><rect x="548" y="294" width="68" height="45" rx="4"/>' .
        '<rect x="462" y="356" width="68" height="45" rx="4"/><rect x="548" y="356" width="68" height="45" rx="4"/>' .
        '</g>' .
        '<rect x="240" y="388" width="160" height="67" rx="4" fill="#0f2744"/>' .
        '<rect x="225" y="380" width="190" height="10" rx="5" fill="#f2994a"/>' .
        '<rect x="275" y="402" width="90" height="53" rx="3" fill="#fef3c7" opacity="0.9"/>' .
        '<rect x="232" y="92" width="176" height="22" rx="11" fill="#173b66" stroke="#f2994a" stroke-width="1.5"/>' .
        '<text x="320" y="107" font-family="Arial, sans-serif" font-size="10.5" font-weight="bold" fill="#ffffff" text-anchor="middle" letter-spacing="1.4">KAMPUS B • LAB TERPADU</text>' .
        '<g fill="#0d9488"><circle cx="120" cy="405" r="38"/><circle cx="148" cy="420" r="28"/><circle cx="92" cy="422" r="25"/><circle cx="695" cy="408" r="35"/><circle cx="668" cy="422" r="26"/></g>' .
        '<rect x="116" y="425" width="8" height="30" fill="#78350f"/><rect x="691" y="425" width="8" height="30" fill="#78350f"/>' .
        '</svg>';

    $fotoDefaultA = 'data:image/svg+xml,' . rawurlencode($svgKampusA);
    $fotoDefaultB = 'data:image/svg+xml,' . rawurlencode($svgKampusB);

    $defaultLokasi = [
        [
            'kode' => 'A',
            'badge' => 'Kampus Utama • Pusat Akademik',
            'nama' => 'Kampus A — Sawangan Depok',
            'deskripsi' =>
                'Gedung rektorat, pusat layanan administrasi akademik, perpustakaan digital, auditorium, dan ruang kuliah utama.',
            'alamat' => 'Jl. Raya Muchtar No. 10, Sawangan, Kota Depok, Jawa Barat 16511',
            'jam' => 'Senin – Sabtu, 08.00 – 17.00 WIB',
            'telepon' => '(021) 7788-9901',
            'koordinat' => '6°24\'14.2"S 106°46\'28.5"E',
            'akses' => '10 Menit dari Gerbang Tol Sawangan (Desari)',
            'unggulan' => ['Gedung Rektorat', 'Perpustakaan Pusat', 'Auditorium Utama', 'Layanan Terpadu'],
            'img' => $fotoDefaultA,
            'map_embed' =>
                'https://maps.google.com/maps?q=Universitas%20Tiara%20Bunda%20Depok&t=&z=15&ie=UTF8&iwloc=&output=embed',
            'map_url' => 'https://maps.google.com/?q=Universitas+Tiara+Bunda+Depok',
        ],
        [
            'kode' => 'B',
            'badge' => 'Kampus Praktik & Riset Terpadu',
            'nama' => 'Kampus B — Margonda Depok',
            'deskripsi' =>
                'Pusat laboratorium kesehatan terpadu, ruang simulasi klinik modern, studio praktik, dan pusat kegiatan mahasiswa.',
            'alamat' => 'Jl. Margonda Raya No. 88, Beji, Kota Depok, Jawa Barat 16424',
            'jam' => 'Senin – Sabtu, 08.00 – 17.00 WIB',
            'telepon' => '(021) 7788-9902',
            'koordinat' => '6°22\'21.8"S 106°49\'54.1"E',
            'akses' => '5 Menit dari Stasiun KRL Pondok Cina & UI',
            'unggulan' => ['Lab Simulasi Klinik', 'Studio Praktik', 'Pusat Riset', 'Student Lounge'],
            'img' => $fotoDefaultB,
            'map_embed' => 'https://maps.google.com/maps?q=Margonda%20Depok&t=&z=15&ie=UTF8&iwloc=&output=embed',
            'map_url' => 'https://maps.google.com/?q=Margonda+Depok',
        ],
    ];

    $rawList = !empty($items) && is_array($items) ? array_values($items) : $defaultLokasi;

    $resolveImg = function (?string $img, string $fallback): string {
        $src = trim((string) $img);
        if ($src === '') {
            return $fallback;
        }
        if (str_starts_with($src, 'data:') || str_starts_with($src, 'http') || str_starts_with($src, '/')) {
            return $src;
        }
        return asset(ltrim($src, '/'));
    };

    $kampusList = [];
    foreach ($rawList as $idx => $row) {
        if (!is_array($row)) {
            continue;
        }
        $kode = $row['kode'] ?? chr(65 + $idx);
        $defaultRef = $defaultLokasi[$idx] ?? $defaultLokasi[0];
        $fallbackSvg = $idx % 2 === 0 ? $fotoDefaultA : $fotoDefaultB;

        $rawChips = $row['unggulan'] ?? $defaultRef['unggulan'];
        if (is_string($rawChips)) {
            $chips = array_values(array_filter(array_map('trim', explode(',', $rawChips))));
        } elseif (is_array($rawChips)) {
            $chips = array_values(array_filter($rawChips));
        } else {
            $chips = $defaultRef['unggulan'];
        }

        $kampusList[] = [
            'kode' => (string) $kode,
            'badge' => (string) ($row['badge'] ?? $defaultRef['badge']),
            'nama' => (string) ($row['nama'] ?? 'Kampus ' . $kode),
            'deskripsi' => (string) ($row['deskripsi'] ?? $defaultRef['deskripsi']),
            'alamat' => (string) ($row['alamat'] ?? $defaultRef['alamat']),
            'jam' => (string) ($row['jam'] ?? $defaultRef['jam']),
            'telepon' => (string) ($row['telepon'] ?? $defaultRef['telepon']),
            'koordinat' => (string) ($row['koordinat'] ?? $defaultRef['koordinat']),
            'akses' => (string) ($row['akses'] ?? $defaultRef['akses']),
            'unggulan' => $chips,
            'img' => $resolveImg($row['img'] ?? ($row['foto'] ?? ''), $fallbackSvg),
            'fallback' => $fallbackSvg,
            'map_embed' => (string) ($row['map_embed'] ?? $defaultRef['map_embed']),
            'map_url' => (string) ($row['map_url'] ?? $defaultRef['map_url']),
        ];
    }

    $active = $kampusList[0] ?? null;
@endphp

@if ($active !== null)
    <section class="sec lokasi-sec" id="lokasi" data-lokasi-root aria-labelledby="lokasi-title">
        {{-- Ornamen Latar Kartografi & Cahaya Mewah --}}
        <div class="lokasi-bg" aria-hidden="true">
            <div class="lokasi-bg-grid"></div>
            <div class="lokasi-bg-glow lokasi-bg-glow--blue"></div>
            <div class="lokasi-bg-glow lokasi-bg-glow--gold"></div>

            <svg class="lokasi-bg-contours" viewBox="0 0 1440 700" fill="none" preserveAspectRatio="xMidYMid slice">
                <path d="M-120 140 C 180 60, 420 260, 740 170 C 1060 80, 1280 230, 1560 150" stroke="currentColor"
                    stroke-width="1.2" />
                <path d="M-120 260 C 210 170, 460 380, 780 280 C 1100 180, 1310 340, 1560 270" stroke="currentColor"
                    stroke-width="1" />
                <path d="M-120 400 C 240 310, 490 510, 820 410 C 1140 310, 1340 470, 1560 400" stroke="currentColor"
                    stroke-width="1.2" />
                <circle cx="1260" cy="170" r="200" stroke="currentColor" stroke-width="1"
                    stroke-dasharray="6 8" />
                <circle cx="160" cy="540" r="220" stroke="currentColor" stroke-width="1"
                    stroke-dasharray="5 9" />
            </svg>
        </div>

        <div class="wrap position-relative">
            {{-- Header & Tab Switcher Kampus --}}
            <div class="lokasi-head-wrap">
                <x-landing.ui.head kick="Lokasi &amp; Lingkungan Kampus" title="Lingkungan belajar di titik strategis"
                    lead="Pilih lokasi kampus untuk melihat gedung perkuliahan, fasilitas unggulan, dan akses transportasi."
                    id="lokasi-title" />

                <div class="lokasi-switcher" role="tablist" aria-label="Pilih lokasi kampus">
                    @foreach ($kampusList as $idx => $k)
                        <button type="button" class="lokasi-switch-btn {{ $idx === 0 ? 'is-active' : '' }}"
                            data-lokasi-tab="{{ $idx }}" role="tab"
                            aria-selected="{{ $idx === 0 ? 'true' : 'false' }}">
                            <span class="lokasi-switch-code">{{ $k['kode'] }}</span>
                            <span class="lokasi-switch-label">
                                <strong>Kampus {{ $k['kode'] }}</strong>
                                <small>{{ \Illuminate\Support\Str::after($k['nama'], '— ') }}</small>
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Data JSON untuk Perpindahan Tab --}}
            <script type="application/json" data-lokasi-json>@json($kampusList)</script>

            {{-- Showcase Utama --}}
            <div class="lokasi-showcase rv" data-lokasi-stage>
                {{-- Sisi Kiri: Bingkai Foto Kampus & Toggle Peta --}}
                <div class="lokasi-visual">
                    <div class="lokasi-visual-bar">
                        <div class="lokasi-view-toggle" role="group" aria-label="Mode tampilan visual">
                            <button type="button" class="lokasi-view-btn is-active" data-view-mode="photo">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" />
                                </svg>
                                <span>Foto Kampus</span>
                            </button>
                            <button type="button" class="lokasi-view-btn" data-view-mode="map">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6" />
                                    <line x1="8" y1="2" x2="8" y2="18" />
                                    <line x1="16" y1="6" x2="16" y2="22" />
                                </svg>
                                <span>Peta Lokasi</span>
                            </button>
                        </div>

                        <span class="lokasi-coord-chip" data-stage-coord>{{ $active['koordinat'] }}</span>
                    </div>

                    {{-- Panel Foto Gedung --}}
                    <div class="lokasi-photo-pane" data-pane-photo>
                        <img src="{{ $active['img'] }}" alt="Foto {{ $active['nama'] }}" width="800" height="560"
                            data-stage-img data-fallback="{{ $active['fallback'] }}">

                        <div class="lokasi-photo-shade" aria-hidden="true"></div>

                        <div class="lokasi-photo-caption">
                            <span class="lokasi-photo-pill">
                                <span class="lokasi-live-dot" aria-hidden="true"></span>
                                <span data-stage-pill>Kampus {{ $active['kode'] }}</span>
                            </span>
                            <p class="lokasi-photo-access" data-stage-akses>{{ $active['akses'] }}</p>
                        </div>
                    </div>

                    {{-- Panel Peta --}}
                    <div class="lokasi-map-pane" data-pane-map hidden>
                        <div class="lokasi-map-fallback">
                            <p>Menampilkan peta interaktif untuk <strong data-map-name>{{ $active['nama'] }}</strong>
                            </p>
                            <a href="{{ $active['map_url'] }}" target="_blank" rel="noopener noreferrer"
                                class="lokasi-map-ext" data-map-ext>
                                Buka langsung di aplikasi Google Maps ↗
                            </a>
                        </div>
                        <iframe src="about:blank" data-src="{{ $active['map_embed'] }}"
                            title="Peta lokasi {{ $active['nama'] }}" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade" allowfullscreen data-stage-iframe></iframe>
                    </div>
                </div>

                {{-- Sisi Kanan: Panel Informasi Royal Navy & Champagne Gold --}}
                <div class="lokasi-detail" aria-live="polite">
                    <div class="lokasi-detail-top">
                        <span class="lokasi-badge" data-stage-badge>{{ $active['badge'] }}</span>
                        <h3 class="lokasi-name" data-stage-nama>{{ $active['nama'] }}</h3>
                        <p class="lokasi-desc" data-stage-desc>{{ $active['deskripsi'] }}</p>
                    </div>

                    @if (count($active['unggulan']))
                        <div class="lokasi-chips" data-stage-chips aria-label="Fasilitas utama kampus">
                            @foreach ($active['unggulan'] as $chip)
                                <span class="lokasi-chip">{{ $chip }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="lokasi-info-grid">
                        <div class="lokasi-info-item lokasi-info-item--full">
                            <span class="lokasi-ico" aria-hidden="true">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.1" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 21s-7-5.686-7-11a7 7 0 1 1 14 0c0 5.314-7 11-7 11Z" />
                                    <circle cx="12" cy="10" r="2.5" />
                                </svg>
                            </span>
                            <div>
                                <span class="lokasi-lbl">Alamat Lengkap</span>
                                <span class="lokasi-val" data-stage-alamat>{{ $active['alamat'] }}</span>
                            </div>
                        </div>

                        <div class="lokasi-info-item">
                            <span class="lokasi-ico" aria-hidden="true">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.1" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="M12 7v5l3 2" />
                                </svg>
                            </span>
                            <div>
                                <span class="lokasi-lbl">Jam Operasional</span>
                                <span class="lokasi-val" data-stage-jam>{{ $active['jam'] }}</span>
                            </div>
                        </div>

                        <div class="lokasi-info-item">
                            <span class="lokasi-ico" aria-hidden="true">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.1" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z" />
                                </svg>
                            </span>
                            <div>
                                <span class="lokasi-lbl">Layanan Informasi</span>
                                <span class="lokasi-val" data-stage-telp>{{ $active['telepon'] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="lokasi-actions">
                        <a href="{{ $active['map_url'] }}" target="_blank" rel="noopener noreferrer"
                            class="lokasi-btn-gold" data-stage-url>
                            <span>Petunjuk Arah Google Maps</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M7 17 17 7M7 7h10v10" />
                            </svg>
                        </a>

                        <button type="button" class="lokasi-btn-copy" data-copy-alamat="{{ $active['alamat'] }}">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.1" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <rect x="9" y="9" width="13" height="13" rx="2" />
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                            </svg>
                            <span data-copy-label>Salin Alamat</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Strip Konektivitas & Lingkungan Kampus --}}
            <div class="lokasi-access-bar rv" aria-label="Keunggulan akses lokasi kampus">
                <div class="lokasi-access-item">
                    <span class="lokasi-access-ico" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="3" width="16" height="16" rx="2" />
                            <path d="M4 11h16M12 3v8M8 19l-2 3M16 19l2 3" />
                        </svg>
                    </span>
                    <div>
                        <strong>Terhubung Stasiun KRL</strong>
                        <span>Akses mudah Commuter Line Jabodetabek</span>
                    </div>
                </div>

                <div class="lokasi-access-item">
                    <span class="lokasi-access-ico" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2" />
                            <circle cx="7" cy="17" r="2" />
                            <circle cx="17" cy="17" r="2" />
                        </svg>
                    </span>
                    <div>
                        <strong>Dekat Akses Tol Utama</strong>
                        <span>Terjangkau via Tol Desari &amp; Tol Cijago</span>
                    </div>
                </div>

                <div class="lokasi-access-item">
                    <span class="lokasi-access-ico" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                    </span>
                    <div>
                        <strong>Kawasan Aman &amp; Nyaman</strong>
                        <span>Area parkir luas &amp; keamanan kampus 24 jam</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
