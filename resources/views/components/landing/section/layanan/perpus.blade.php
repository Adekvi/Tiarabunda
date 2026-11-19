@props(['highlight' => null, 'items' => [], 'selectedPerpus' => null])

@php
    $items = collect($items);
    $portalPusatUrl =
        data_get($highlight, 'data_tambahan.url_portal') ?:
        \App\Services\Landing\Layanan\PerpusService::DEFAULT_PORTAL_URL;

    $getIcon = function ($ikon) {
        return match ($ikon) {
            'clock'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
            'layers'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>',
            'shield-alert'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
            default
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
        };
    };

    $getColor = function ($warna) {
        return match ($warna) {
            'green' => 'prs__icon--green',
            'orange' => 'prs__icon--orange',
            'red' => 'prs__icon--red',
            default => 'prs__icon--blue',
        };
    };
@endphp

<section class="prs">
    <div class="wrap prs__wrap">

        {{-- ========================================================= --}}
        {{-- 1. TAMPILAN PERPUSTAKAAN FAKULTAS (DALAM PENGEMBANGAN)    --}}
        {{-- ========================================================= --}}
        @if ($selectedPerpus)
            @php
                $meta = data_get($selectedPerpus, 'data_tambahan', []);
                $daftarProdi = collect($meta['daftar_prodi'] ?? []);
            @endphp

            <div class="prs__back-wrap rv">
                <a href="/layanan/perpustakaan" class="prs__back-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Layanan Perpustakaan
                </a>
            </div>

            <div class="prs__dev-box rv">
                <div class="prs__dev-badge">
                    <span class="prs__dev-dot" aria-hidden="true"></span>
                    Sedang Dalam Tahap Pengembangan
                </div>

                <div class="prs__dev-icon" aria-hidden="true">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                        <path d="M12 6v4" />
                        <path d="M12 14h.01" />
                    </svg>
                </div>

                <h2 class="prs__dev-title">{{ data_get($selectedPerpus, 'judhul') }}</h2>

                <p class="prs__dev-desc">
                    Portal layanan khusus <strong>{{ data_get($selectedPerpus, 'judhul') }}</strong> saat ini sedang
                    dalam tahap pengerjaan dan integrasi sistem katalog digital. Selama proses berlangsung, sivitas
                    akademika tetap dapat mengakses seluruh koleksi buku, jurnal, dan referensi melalui
                    <strong>Portal Perpustakaan Pusat</strong>.
                </p>

                @if ($daftarProdi->isNotEmpty())
                    <div class="prs__dev-prodi">
                        <small>Cakupan Program Studi:</small>
                        <div class="prs__dev-tags">
                            @foreach ($daftarProdi as $prodiNama)
                                <span>{{ $prodiNama }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (!empty($meta['jam_layanan']) || !empty($meta['kontak']) || !empty($meta['email']))
                    <div class="prs__dev-meta">
                        @if (!empty($meta['jam_layanan']))
                            <div>
                                <small>Jam Layanan Fisik</small>
                                <strong>{{ $meta['jam_layanan'] }}</strong>
                            </div>
                        @endif
                        @if (!empty($meta['kontak']))
                            <div>
                                <small>Kontak Layanan</small>
                                <strong>{{ $meta['kontak'] }}</strong>
                            </div>
                        @endif
                        @if (!empty($meta['email']))
                            <div>
                                <small>Email Pustakawan</small>
                                <strong>{{ $meta['email'] }}</strong>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="prs__dev-actions">
                    <a href="{{ $portalPusatUrl }}" target="_blank" rel="noopener noreferrer" class="prs__portal-btn">
                        <span>Akses Portal Perpustakaan Pusat</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                            <polyline points="15 3 21 3 21 9" />
                            <line x1="10" y1="14" x2="21" y2="3" />
                        </svg>
                    </a>
                    <a href="/layanan/perpustakaan" class="prs__back-secondary">
                        Lihat Semua Layanan Perpustakaan
                    </a>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- 2. HALAMAN UTAMA PERPUSTAKAAN                             --}}
            {{-- ========================================================= --}}
        @else
            <div class="prs__intro rv">
                <p class="prs__eyebrow">Layanan Akademik</p>
                <h2 class="prs__title">Perpustakaan</h2>
                <p class="prs__lead">
                    Pusat sumber belajar yang menyediakan koleksi literatur, jurnal, dan ruang baca nyaman untuk
                    mendukung riset dan perkuliahan Anda.
                </p>

                <div class="prs__intro-actions">
                    <a href="{{ $portalPusatUrl }}" target="_blank" rel="noopener noreferrer" class="prs__portal-btn">
                        <span>Kunjungi Portal Perpustakaan Pusat</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                            <polyline points="15 3 21 3 21 9" />
                            <line x1="10" y1="14" x2="21" y2="3" />
                        </svg>
                    </a>
                    <span class="prs__portal-url">perpus.poltektiarabundadepok.ac.id</span>
                </div>
            </div>

            {{-- Highlight Box --}}
            @if ($highlight)
                @php
                    $hlMeta = data_get($highlight, 'data_tambahan', []);
                @endphp
                <div class="prs__highlight rv">
                    <div class="prs__hl-icon">
                        {!! $getIcon(data_get($highlight, 'data_tambahan.ikon', 'book-open')) !!}
                    </div>
                    <div class="prs__hl-content">
                        <h3>{{ data_get($highlight, 'judhul') }}</h3>
                        <div class="prs__hl-text prose">
                            {!! data_get($highlight, 'isi') !!}
                        </div>

                        @if (!empty($hlMeta['jam_layanan']) || !empty($hlMeta['kontak']) || !empty($hlMeta['email']))
                            <div class="prs__hl-meta">
                                @if (!empty($hlMeta['jam_layanan']))
                                    <span><strong>Jam Layanan:</strong> {{ $hlMeta['jam_layanan'] }}</span>
                                @endif
                                @if (!empty($hlMeta['kontak']))
                                    <span><strong>Kontak:</strong> {{ $hlMeta['kontak'] }}</span>
                                @endif
                                @if (!empty($hlMeta['email']))
                                    <span><strong>Email:</strong> {{ $hlMeta['email'] }}</span>
                                @endif
                            </div>
                        @endif

                        <div class="prs__hl-footer">
                            <a href="{{ $portalPusatUrl }}" target="_blank" rel="noopener noreferrer"
                                class="prs__card-link">
                                Buka Katalog & Layanan Digital
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                    <polyline points="15 3 21 3 21 9" />
                                    <line x1="10" y1="14" x2="21" y2="3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Grid Card Perpustakaan Fakultas & Layanan --}}
            @if ($items->isNotEmpty())
                <div class="prs__grid">
                    @foreach ($items as $item)
                        @php
                            $itemPortal = data_get($item, 'data_tambahan.url_portal');
                            $isExternal =
                                $itemPortal &&
                                \Illuminate\Support\Str::startsWith($itemPortal, ['http://', 'https://']);
                            $isDev = data_get($item, 'data_tambahan.status_portal') === 'pengembangan';
                        @endphp
                        <div class="prs__card rv">
                            <div class="prs__card-top">
                                <div class="prs__card-icon {{ $getColor(data_get($item, 'data_tambahan.warna')) }}">
                                    {!! $getIcon(data_get($item, 'data_tambahan.ikon')) !!}
                                </div>
                                @if ($isDev)
                                    <span class="prs__card-status">Dalam Proses</span>
                                @endif
                            </div>

                            <h3 class="prs__card-title">{{ data_get($item, 'judhul') }}</h3>
                            <div class="prs__card-desc prose">
                                {!! data_get($item, 'isi') !!}
                            </div>

                            @if ($itemPortal)
                                <div class="prs__card-footer">
                                    <a href="{{ $itemPortal }}"
                                        @if ($isExternal) target="_blank" rel="noopener noreferrer" @endif
                                        class="prs__card-link">
                                        {{ $isDev ? 'Buka Portal Fakultas' : 'Akses Layanan' }}
                                        @if ($isExternal)
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" aria-hidden="true">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                                <polyline points="15 3 21 3 21 9" />
                                                <line x1="10" y1="14" x2="21" y2="3" />
                                            </svg>
                                        @else
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" aria-hidden="true">
                                                <path d="M5 12h14M12 5l7 7-7 7" />
                                            </svg>
                                        @endif
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Informasi Dasar Perpustakaan (Statis) --}}
            <div class="prs__base-sec rv">
                <div class="prs__base-header">
                    <p class="prs__eyebrow">Informasi Dasar</p>
                    <h3 class="prs__base-heading">Fasilitas & Ketentuan Layanan Perpustakaan</h3>
                </div>

                <div class="prs__base-grid">
                    {{-- 1. Ruang Baca & Diskusi Kelompok --}}
                    <div class="prs__base-card">
                        <div class="prs__base-head">
                            <div class="prs__card-icon prs__icon--green">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="prs__base-title">Ruang Baca & Diskusi Kelompok</h4>
                                <span class="prs__base-sub">Senin–Jumat, 08.00–16.00 WIB</span>
                            </div>
                        </div>

                        <ul class="prs__base-list">
                            <li>Ruang baca ber-AC yang tenang dengan pencahayaan nyaman dan akses Wi-Fi kampus.</li>
                            <li>Area diskusi kelompok berkapasitas 4–10 orang yang dilengkapi meja kolaborasi dan
                                perangkat presentasi.</li>
                            <li>Area komputer katalog (OPAC) dan akses jurnal elektronik untuk penelusuran referensi
                                tugas akhir.</li>
                        </ul>
                    </div>

                    {{-- 2. Ketentuan Peminjaman & Keanggotaan --}}
                    <div class="prs__base-card">
                        <div class="prs__base-head">
                            <div class="prs__card-icon prs__icon--red">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                    <line x1="12" y1="8" x2="12" y2="12" />
                                    <line x1="12" y1="16" x2="12.01" y2="16" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="prs__base-title">Ketentuan Peminjaman & Keanggotaan</h4>
                                <span class="prs__base-sub">Berlaku bagi Mahasiswa & Dosen</span>
                            </div>
                        </div>

                        <ul class="prs__base-list">
                            <li>Keanggotaan aktif otomatis bagi seluruh mahasiswa dan dosen terdaftar dengan menunjukkan
                                KTM atau kartu identitas kampus.</li>
                            <li>Maksimal peminjaman sebanyak <strong>3 eksemplar buku</strong> untuk jangka waktu
                                <strong>7 hari kerja</strong> dan dapat diperpanjang 1 kali.
                            </li>
                            <li>Koleksi referensi khusus (kamus, ensiklopedia, skripsi/tugas akhir) hanya dapat dibaca
                                di tempat.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="prs__cta rv">
                <div>
                    <h3>Cari koleksi buku, jurnal, dan repository kampus</h3>
                    <p>Akses katalog online (OPAC) dan koleksi digital melalui Portal Perpustakaan Universitas Tiara
                        Bunda.</p>
                </div>
                <div class="prs__cta-actions">
                    <a class="btn" href="{{ $portalPusatUrl }}" target="_blank" rel="noopener noreferrer">
                        Buka Portal Perpustakaan &rarr;
                    </a>
                    <a class="prs__cta-secondary" href="#">Hubungi Pustakawan</a>
                </div>
            </div>
        @endif

    </div>
</section>
