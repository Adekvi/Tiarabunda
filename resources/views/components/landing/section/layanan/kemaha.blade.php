@props([
    'highlight' => null,
    'items' => [],
    'beasiswaInfo' => null,
    'selectedKemahasiswaan' => null,
    'showBeasiswaList' => false,
    'beasiswaList' => [],
    'selectedBeasiswa' => null,
])

@php
    $items = collect($items);
    $beasiswaItems = collect($beasiswaList);

    $allKemahasiswaan = collect([$highlight])
        ->filter()
        ->merge($items);

    $getIcon = function ($ikon) {
        return match ($ikon) {
            'users'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            'star'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
            'lightbulb'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1.25.45 2.39 1.5 3.5.76.76 1.23 1.52 1.41 2.5"/></svg>',
            'heart'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
            'graduation-cap'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>',
            default
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
        };
    };

    $getColor = function ($warna) {
        return match ($warna) {
            'green' => 'kms__icon--green',
            'orange' => 'kms__icon--orange',
            'red' => 'kms__icon--red',
            'purple' => 'kms__icon--purple',
            default => 'kms__icon--blue',
        };
    };
@endphp

<section class="kms">
    <div class="wrap kms__wrap">

        {{-- ========================================================= --}}
        {{-- 1. DETAIL SALAH SATU BEASISWA                             --}}
        {{-- ========================================================= --}}
        @if ($selectedBeasiswa)
            <div class="bsw__intro rv">
                <div class="bsw__back-wrap">
                    <a href="/layanan/kemahasiswaan/beasiswa" class="bsw__back-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Pilihan Beasiswa
                    </a>
                </div>

                <p class="bsw__eyebrow">Detail Program Beasiswa</p>
                <h2 class="bsw__title">{{ data_get($selectedBeasiswa, 'jeneng') }}</h2>

                @if (data_get($selectedBeasiswa, 'katrangan'))
                    <div class="bsw__lead prose">
                        {!! data_get($selectedBeasiswa, 'katrangan') !!}
                    </div>
                @endif
            </div>

            <div class="bsw__detail-grid">
                <div class="bsw__panel rv">
                    <h3 class="bsw__panel-title">Persyaratan</h3>
                    @if (data_get($selectedBeasiswa, 'syarat'))
                        <div class="bsw__rich-text prose">
                            {!! data_get($selectedBeasiswa, 'syarat') !!}
                        </div>
                    @else
                        <p class="bsw__empty">Belum ada persyaratan spesifik.</p>
                    @endif
                </div>

                <div class="bsw__panel rv bsw__panel--highlight">
                    <h3 class="bsw__panel-title">Cakupan Beasiswa</h3>
                    @if (data_get($selectedBeasiswa, 'jangkepan'))
                        <div class="bsw__highlight-text">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                            </svg>
                            <p>{{ data_get($selectedBeasiswa, 'jangkepan') }}</p>
                        </div>
                    @else
                        <p class="bsw__empty">Informasi cakupan beasiswa belum tersedia.</p>
                    @endif
                </div>
            </div>

            <div class="bsw__cta rv">
                <div>
                    <h3>Tertarik dengan beasiswa ini?</h3>
                    <p>Pastikan Anda memenuhi persyaratan dan daftar sebelum batas waktu berakhir.</p>
                </div>
                <a class="btn" href="https://poltektiarabunda.pmbonline.siakad.tech/register" target="_blank"
                    rel="noopener">
                    Daftar Sekarang &rarr;
                </a>
            </div>

            {{-- ========================================================= --}}
            {{-- 2. DAFTAR PILIHAN BEASISWA (SAAT INFO BEASISWA DIKLIK)    --}}
            {{-- ========================================================= --}}
        @elseif ($showBeasiswaList)
            <div class="bsw__intro rv">
                <div class="bsw__back-wrap">
                    <a href="/layanan/kemahasiswaan" class="bsw__back-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Kemahasiswaan
                    </a>
                </div>

                <p class="bsw__eyebrow">Dukungan Finansial</p>
                <h2 class="bsw__title">{{ data_get($beasiswaInfo, 'judhul', 'Program & Layanan Beasiswa') }}</h2>
                <div class="bsw__lead prose">
                    @if (data_get($beasiswaInfo, 'isi'))
                        {!! data_get($beasiswaInfo, 'isi') !!}
                    @else
                        <p>Tersedia berbagai pilihan beasiswa untuk mendukung studi Anda, mulai dari prestasi akademik
                            hingga bantuan pemerintah.</p>
                    @endif
                </div>
            </div>

            {{-- Info Kontak Layanan Beasiswa dari Master Kemahasiswaan --}}
            @php
                $bswMeta = data_get($beasiswaInfo, 'data_tambahan', []);
            @endphp
            @if (!empty($bswMeta['kontak']) || !empty($bswMeta['email']) || !empty($bswMeta['lokasi']))
                <div class="kms__meta-bar rv" style="margin-bottom: 36px;">
                    @if (!empty($bswMeta['lokasi']))
                        <div class="kms__meta-item">
                            <small>Lokasi Layanan</small>
                            <strong>{{ $bswMeta['lokasi'] }}</strong>
                        </div>
                    @endif
                    @if (!empty($bswMeta['kontak']))
                        <div class="kms__meta-item">
                            <small>Kontak</small>
                            <strong>{{ $bswMeta['kontak'] }}</strong>
                        </div>
                    @endif
                    @if (!empty($bswMeta['email']))
                        <div class="kms__meta-item">
                            <small>Email</small>
                            <strong>{{ $bswMeta['email'] }}</strong>
                        </div>
                    @endif
                </div>
            @endif

            @if ($beasiswaItems->isNotEmpty())
                <div class="bsw__grid" style="margin-bottom: 48px;">
                    @foreach ($beasiswaItems as $b)
                        <article class="bsw__card rv">
                            <div class="bsw__icon-wrap">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                    <path d="M6 12v5c3 3 9 3 12 0v-5" />
                                </svg>
                            </div>

                            <h3 class="bsw__card-title">{{ data_get($b, 'jeneng') }}</h3>

                            @if (data_get($b, 'katrangan'))
                                <div class="bsw__card-desc prose">
                                    {!! data_get($b, 'katrangan') !!}
                                </div>
                            @endif

                            @if (data_get($b, 'jangkepan'))
                                <div class="bsw__card-benefit">
                                    <span>Benefit:</span> {{ Str::limit(strip_tags(data_get($b, 'jangkepan')), 70) }}
                                </div>
                            @endif

                            <div class="bsw__card-footer">
                                <a href="/layanan/kemahasiswaan/beasiswa/{{ data_get($b, 'tandha') }}"
                                    class="bsw__card-link">
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
            @else
                <div class="bsw__empty-all rv" style="margin-bottom: 48px;">
                    <h3>Belum ada program beasiswa</h3>
                    <p>Informasi beasiswa akan tampil setelah diisi melalui panel superadmin.</p>
                </div>
            @endif

            <div class="bsw__cta rv">
                <div>
                    <h3>Butuh bantuan konsultasi beasiswa?</h3>
                    <p>Hubungi Biro Kemahasiswaan untuk informasi jadwal seleksi dan kelengkapan berkas.</p>
                </div>
                <a class="btn" href="https://wa.me/6285773825001">Hubungi Kami &rarr;</a>
            </div>

            {{-- ========================================================= --}}
            {{-- 3. DETAIL ITEM KEMAHASISWAAN (BEM, UKM, KONSELING, DLL)   --}}
            {{-- ========================================================= --}}
        @elseif ($selectedKemahasiswaan)
            @php
                $meta = data_get($selectedKemahasiswaan, 'data_tambahan', []);
            @endphp

            <div class="bsw__back-wrap rv">
                <a href="/layanan/kemahasiswaan" class="bsw__back-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Kemahasiswaan
                </a>
            </div>

            <article class="kms__detail-box rv">
                <div class="kms__detail-head">
                    <div class="kms__card-icon {{ $getColor($meta['warna'] ?? null) }}">
                        {!! $getIcon($meta['ikon'] ?? 'users') !!}
                    </div>
                    <div>
                        <p class="kms__eyebrow" style="margin-bottom: 8px;">Detail Kemahasiswaan</p>
                        <h2 class="kms__detail-title">{{ data_get($selectedKemahasiswaan, 'judhul') }}</h2>
                    </div>
                </div>

                <div class="kms__detail-body prose">
                    {!! data_get($selectedKemahasiswaan, 'isi') !!}
                </div>

                @if (!empty($meta['lokasi']) || !empty($meta['kontak']) || !empty($meta['email']) || !empty($meta['url_portal']))
                    <div class="kms__meta-bar" style="margin-top: 28px;">
                        @if (!empty($meta['lokasi']))
                            <div class="kms__meta-item">
                                <small>Lokasi Sekretariat / Layanan</small>
                                <strong>{{ $meta['lokasi'] }}</strong>
                            </div>
                        @endif
                        @if (!empty($meta['kontak']))
                            <div class="kms__meta-item">
                                <small>Kontak</small>
                                <strong>{{ $meta['kontak'] }}</strong>
                            </div>
                        @endif
                        @if (!empty($meta['email']))
                            <div class="kms__meta-item">
                                <small>Email</small>
                                <strong>{{ $meta['email'] }}</strong>
                            </div>
                        @endif
                        @if (!empty($meta['url_portal']))
                            <div class="kms__meta-item">
                                <small>Portal Informasi</small>
                                <a href="{{ $meta['url_portal'] }}" target="_blank" rel="noopener noreferrer">
                                    Buka Portal &rarr;
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </article>

            @php
                $otherItems = $allKemahasiswaan->reject(
                    fn($i) => data_get($i, 'tandha') === data_get($selectedKemahasiswaan, 'tandha'),
                );
            @endphp

            @if ($otherItems->isNotEmpty())
                <div class="kms__related rv">
                    <h3 class="kms__related-title">Layanan & Organisasi Lainnya</h3>
                    <div class="kms__grid">
                        @foreach ($otherItems as $other)
                            <article class="kms__card">
                                <div class="kms__card-icon {{ $getColor(data_get($other, 'data_tambahan.warna')) }}">
                                    {!! $getIcon(data_get($other, 'data_tambahan.ikon')) !!}
                                </div>
                                <h4 class="kms__card-title">{{ data_get($other, 'judhul') }}</h4>
                                <div class="kms__card-desc kms__card-desc--clamp prose">
                                    {!! data_get($other, 'isi') !!}
                                </div>
                                <div class="kms__card-footer">
                                    <a href="/layanan/kemahasiswaan/{{ data_get($other, 'tandha') }}"
                                        class="kms__card-link">
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

            <div class="kms__cta rv">
                <div>
                    <h3>Ingin bergabung dengan organisasi?</h3>
                    <p>Daftarkan diri Anda pada masa pengenalan kehidupan kampus (PKKMB) atau hubungi bagian
                        kemahasiswaan.</p>
                </div>
                <a class="btn" href="https://wa.me/6285773825001">Hubungi Kami &rarr;</a>
            </div>

            {{-- ========================================================= --}}
            {{-- 4. HALAMAN UTAMA KEMAHASISWAAN (HANYA INFO BEASISWA SAJA) --}}
            {{-- ========================================================= --}}
        @else
            <div class="kms__intro rv">
                <p class="kms__eyebrow">Organisasi & Kegiatan</p>
                <h2 class="kms__title">Kemahasiswaan</h2>
                <p class="kms__lead">
                    Wadah pengembangan diri, minat, bakat, dan soft skill mahasiswa melalui berbagai organisasi, layanan
                    kesejahteraan, serta informasi beasiswa.
                </p>
            </div>

            @if ($highlight)
                <div class="kms__highlight rv">
                    <div class="kms__hl-icon">
                        {!! $getIcon(data_get($highlight, 'data_tambahan.ikon', 'users')) !!}
                    </div>
                    <div class="kms__hl-content">
                        <h3>{{ data_get($highlight, 'judhul') }}</h3>
                        <div class="kms__hl-text prose">
                            {!! data_get($highlight, 'isi') !!}
                        </div>
                        <div class="kms__hl-actions">
                            <a href="/layanan/kemahasiswaan/{{ data_get($highlight, 'tandha') }}"
                                class="kms__card-link">
                                Lihat Selengkapnya
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if ($items->isNotEmpty())
                <div class="kms__grid">
                    @foreach ($items as $item)
                        <article class="kms__card rv">
                            <div class="kms__card-icon {{ $getColor(data_get($item, 'data_tambahan.warna')) }}">
                                {!! $getIcon(data_get($item, 'data_tambahan.ikon')) !!}
                            </div>
                            <h3 class="kms__card-title">{{ data_get($item, 'judhul') }}</h3>
                            <div class="kms__card-desc kms__card-desc--clamp prose">
                                {!! data_get($item, 'isi') !!}
                            </div>
                            <div class="kms__card-footer">
                                <a href="/layanan/kemahasiswaan/{{ data_get($item, 'tandha') }}"
                                    class="kms__card-link">
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

            <div class="kms__cta rv">
                <div>
                    <h3>Ingin bergabung dengan organisasi?</h3>
                    <p>Daftarkan diri Anda pada masa pengenalan kehidupan kampus (PKKMB) atau hubungi bagian
                        kemahasiswaan.</p>
                </div>
                <a class="btn" href="https://wa.me/6285773825001">Hubungi Kami &rarr;</a>
            </div>
        @endif

    </div>
</section>
