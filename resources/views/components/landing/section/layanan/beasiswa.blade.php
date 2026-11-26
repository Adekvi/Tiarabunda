@props([
    'beasiswaList' => [],
    'beasiswaInfo' => null,
    'selectedBeasiswa' => null,
])

@php
    $beasiswaItems = collect($beasiswaList)->values();
    $bswMeta = data_get($beasiswaInfo, 'data_tambahan', []);

    $kontakCfg = config('landing.site.kontak', []);
    $rawPhone =
        (string) ($bswMeta['kontak'] ?? ($kontakCfg['telp'] ?? config('landing.site.hotline', '0811-1701-4000')));
    $waUrl = (string) ($kontakCfg['wa'] ?? 'https://wa.me/6281117014000');
    $registerUrl = (string) config(
        'landing.welcome_popup.register_url',
        'https://poltektiarabunda.pmbonline.siakad.tech/register',
    );
    $namaKampus = (string) config('landing.site.nama', 'Universitas Tiara Bunda');
@endphp

<section class="bsw" id="beasiswa-section" data-bsw-root>
    <div class="wrap bsw__wrap">

        @if ($selectedBeasiswa)
            @php
                $otherBeasiswa = $beasiswaItems
                    ->reject(fn($item) => data_get($item, 'tandha') === data_get($selectedBeasiswa, 'tandha'))
                    ->take(3)
                    ->values();
            @endphp

            {{-- ==================== DETAIL PROGRAM BEASISWA ==================== --}}
            <div class="bsw__intro rv">
                <div class="bsw__back-wrap">
                    <a href="{{ url('/layanan/beasiswa') }}" class="bsw__back-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                        <span>Kembali ke Daftar Beasiswa</span>
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
                    <div class="bsw__panel-head">
                        <span class="bsw__panel-badge">Ketentuan</span>
                        <h3 class="bsw__panel-title">Persyaratan Pendaftaran</h3>
                    </div>
                    @if (data_get($selectedBeasiswa, 'syarat'))
                        <div class="bsw__rich-text prose">
                            {!! data_get($selectedBeasiswa, 'syarat') !!}
                        </div>
                    @else
                        <p class="bsw__empty">Persyaratan detail dapat dikonsultasikan langsung dengan tim admisi.</p>
                    @endif
                </div>

                <div class="bsw__panel bsw__panel--highlight rv">
                    <div class="bsw__panel-head">
                        <span class="bsw__panel-badge bsw__panel-badge--gold">Manfaat</span>
                        <h3 class="bsw__panel-title">Cakupan Beasiswa</h3>
                    </div>
                    @if (data_get($selectedBeasiswa, 'jangkepan'))
                        <div class="bsw__highlight-text prose">
                            {!! data_get($selectedBeasiswa, 'jangkepan') !!}
                        </div>
                    @else
                        <p class="bsw__empty">Informasi cakupan beasiswa belum tersedia.</p>
                    @endif
                </div>
            </div>

            <div class="bsw__cta rv">
                <div>
                    <h3>Tertarik mengajukan {{ data_get($selectedBeasiswa, 'jeneng') }}?</h3>
                    <p>Siapkan dokumen persyaratan Anda dan lakukan pendaftaran sebelum kuota terpenuhi.</p>
                </div>
                <div class="bsw__cta-actions">
                    <a class="btn bsw__btn-primary" href="{{ $registerUrl }}" target="_blank" rel="noopener">
                        Daftar Sekarang &rarr;
                    </a>
                    <a class="btn bsw__btn-outline"
                        href="{{ $waUrl }}?text={{ rawurlencode('Halo ' . $namaKampus . ', saya ingin bertanya mengenai program ' . data_get($selectedBeasiswa, 'jeneng')) }}"
                        target="_blank" rel="noopener">
                        Tanya via WhatsApp
                    </a>
                </div>
            </div>

            @if ($otherBeasiswa->isNotEmpty())
                <div class="bsw__related rv">
                    <div class="bsw__related-head">
                        <p class="bsw__eyebrow">Pilihan Lainnya</p>
                        <h3>Program Beasiswa Terkait</h3>
                    </div>
                    <div class="bsw__grid">
                        @foreach ($otherBeasiswa as $other)
                            <article class="bsw__card">
                                <div class="bsw__icon-wrap">
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                        <path d="M6 12v5c3 3 9 3 12 0v-5" />
                                    </svg>
                                </div>

                                <h4 class="bsw__card-title">{{ data_get($other, 'jeneng') }}</h4>

                                @if (data_get($other, 'katrangan'))
                                    <p class="bsw__card-desc">
                                        {{ \Illuminate\Support\Str::limit(strip_tags(data_get($other, 'katrangan')), 120) }}
                                    </p>
                                @endif

                                @if (data_get($other, 'tandha'))
                                    <div class="bsw__card-footer">
                                        <a href="{{ url('/layanan/beasiswa/' . data_get($other, 'tandha')) }}"
                                            class="bsw__card-link">
                                            <span>Lihat Selengkapnya</span>
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M5 12h14M12 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            {{-- ==================== DAFTAR PROGRAM BEASISWA ==================== --}}
            <div class="bsw__header rv">
                <div class="bsw__intro">
                    <p class="bsw__eyebrow">Dukungan Finansial &amp; Prestasi</p>
                    <h2 class="bsw__title">{{ data_get($beasiswaInfo, 'judhul', 'Program & Layanan Beasiswa') }}</h2>
                    <div class="bsw__lead prose">
                        @if (data_get($beasiswaInfo, 'isi'))
                            {!! data_get($beasiswaInfo, 'isi') !!}
                        @else
                            <p>{{ $namaKampus }} menyediakan berbagai skema beasiswa dan bantuan biaya pendidikan
                                bagi
                                calon mahasiswa maupun mahasiswa aktif berprestasi.</p>
                        @endif
                    </div>
                </div>

                @if ($beasiswaItems->count() > 2)
                    <div class="bsw__search-wrap">
                        <input type="search" class="bsw__search-input" placeholder="Cari program beasiswa..."
                            aria-label="Cari program beasiswa" data-bsw-search>
                    </div>
                @endif
            </div>

            @if (!empty($bswMeta['kontak']) || !empty($bswMeta['email']) || !empty($bswMeta['lokasi']))
                <div class="bsw__meta-bar rv">
                    @if (!empty($bswMeta['lokasi']))
                        <div class="bsw__meta-item">
                            <small>Lokasi Layanan</small>
                            <strong>{{ $bswMeta['lokasi'] }}</strong>
                        </div>
                    @endif
                    @if (!empty($bswMeta['kontak']))
                        <div class="bsw__meta-item">
                            <small>Kontak Layanan</small>
                            <strong>{{ $bswMeta['kontak'] }}</strong>
                        </div>
                    @endif
                    @if (!empty($bswMeta['email']))
                        <div class="bsw__meta-item">
                            <small>Email Resmi</small>
                            <strong>{{ $bswMeta['email'] }}</strong>
                        </div>
                    @endif
                </div>
            @endif

            @if ($beasiswaItems->isNotEmpty())
                <div class="bsw__grid" data-bsw-grid>
                    @foreach ($beasiswaItems as $b)
                        @php
                            $searchText = strtolower(
                                (string) data_get($b, 'jeneng', '') .
                                    ' ' .
                                    strip_tags((string) data_get($b, 'katrangan', '')) .
                                    ' ' .
                                    strip_tags((string) data_get($b, 'jangkepan', '')),
                            );
                        @endphp
                        <article class="bsw__card rv" data-bsw-item data-search="{{ $searchText }}">
                            <div class="bsw__card-top">
                                <div class="bsw__icon-wrap">
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                        <path d="M6 12v5c3 3 9 3 12 0v-5" />
                                    </svg>
                                </div>
                                <span class="bsw__card-badge">Beasiswa</span>
                            </div>

                            <h3 class="bsw__card-title">{{ data_get($b, 'jeneng') }}</h3>

                            @if (data_get($b, 'katrangan'))
                                <p class="bsw__card-desc">
                                    {{ \Illuminate\Support\Str::limit(strip_tags(data_get($b, 'katrangan')), 135) }}
                                </p>
                            @endif

                            @if (data_get($b, 'jangkepan'))
                                <div class="bsw__card-benefit">
                                    <span>Cakupan:</span>
                                    {{ \Illuminate\Support\Str::limit(strip_tags(data_get($b, 'jangkepan')), 80) }}
                                </div>
                            @endif

                            @if (data_get($b, 'tandha'))
                                <div class="bsw__card-footer">
                                    <a href="{{ url('/layanan/beasiswa/' . data_get($b, 'tandha')) }}"
                                        class="bsw__card-link">
                                        <span>Lihat Syarat &amp; Detail</span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14M12 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>

                <div class="bsw__empty-all" data-bsw-empty-search hidden>
                    <h3>Program beasiswa tidak ditemukan</h3>
                    <p>Coba gunakan kata kunci pencarian lainnya.</p>
                </div>
            @else
                <div class="bsw__empty-all rv">
                    <h3>Informasi program beasiswa sedang diperbarui</h3>
                    <p>Silakan hubungi tim layanan admisi &amp; kemahasiswaan untuk informasi KIP Kuliah, beasiswa
                        prestasi, maupun keringanan biaya pendidikan.</p>
                </div>
            @endif

            <div class="bsw__cta rv">
                <div>
                    <h3>Butuh konsultasi pilihan beasiswa?</h3>
                    <p>Hubungi tim admisi &amp; kemahasiswaan ({{ $rawPhone }}) untuk jadwal seleksi dan
                        kelengkapan berkas.</p>
                </div>
                <div class="bsw__cta-actions">
                    <a class="btn bsw__btn-primary"
                        href="{{ $waUrl }}?text={{ rawurlencode('Halo ' . $namaKampus . ', saya ingin berkonsultasi mengenai program beasiswa.') }}"
                        target="_blank" rel="noopener">
                        Konsultasi WhatsApp &rarr;
                    </a>
                    <a class="btn bsw__btn-outline" href="{{ $registerUrl }}" target="_blank" rel="noopener">
                        Pendaftaran PMB
                    </a>
                </div>
            </div>
        @endif

    </div>
</section>
