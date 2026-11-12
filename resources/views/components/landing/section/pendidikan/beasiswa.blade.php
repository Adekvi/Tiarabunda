@props(['beasiswaList' => [], 'selectedBeasiswa' => null])

@php
    $items = collect($beasiswaList);
@endphp

<section class="bsw">
    <div class="wrap bsw__wrap">

        @if ($selectedBeasiswa)
            {{-- DETAIL BEASISWA --}}
            <div class="bsw__intro rv">
                <div class="bsw__back-wrap">
                    <a href="/pendidikan/beasiswa" class="bsw__back-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Daftar Beasiswa
                    </a>
                </div>

                <p class="bsw__eyebrow">Detail Program Beasiswa</p>
                <h2 class="bsw__title">{{ data_get($selectedBeasiswa, 'jeneng') }}</h2>

                @if (data_get($selectedBeasiswa, 'katrangan'))
                    {{-- PERBAIKAN 1: Hapus nl2br(e()) dan gunakan {!! !!} --}}
                    <div class="bsw__lead prose">
                        {!! data_get($selectedBeasiswa, 'katrangan') !!}
                    </div>
                @endif
            </div>

            <div class="bsw__detail-grid">
                {{-- Syarat --}}
                <div class="bsw__panel rv">
                    <h3 class="bsw__panel-title">Persyaratan</h3>
                    {{-- PERBAIKAN 2: Hapus logika pemecahan list dan langsung render --}}
                    @if (data_get($selectedBeasiswa, 'syarat'))
                        <div class="bsw__rich-text prose">
                            {!! data_get($selectedBeasiswa, 'syarat') !!}
                        </div>
                    @else
                        <p class="bsw__empty">Belum ada persyaratan spesifik.</p>
                    @endif
                </div>

                {{-- Cakupan / Jangkepan --}}
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
                <a class="btn" href="/pendidikan/penerimaan-mahasiswa-baru">Daftar Sekarang &rarr;</a>
            </div>
        @else
            {{-- DAFTAR BEASISWA --}}
            <div class="bsw__intro rv">
                <p class="bsw__eyebrow">Dukungan Finansial</p>
                <h2 class="bsw__title">Program Beasiswa</h2>
                <p class="bsw__lead">
                    Tersedia berbagai pilihan beasiswa untuk mendukung studi Anda, mulai dari prestasi akademik hingga
                    bantuan pemerintah.
                </p>
            </div>

            @if ($items->isNotEmpty())
                <div class="bsw__grid">
                    @foreach ($items as $b)
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

                            @if (data_get($selectedBeasiswa, 'katrangan'))
                                <div class="bsw__lead prose">
                                    {!! data_get($selectedBeasiswa, 'katrangan') !!}
                                </div>
                            @endif

                            @if (data_get($b, 'jangkepan'))
                                <div class="bsw__card-benefit">
                                    <span>Benefit:</span> {{ Str::limit(data_get($b, 'jangkepan'), 60) }}
                                </div>
                            @endif

                            <div class="bsw__card-footer">
                                <a href="/pendidikan/beasiswa/{{ data_get($b, 'tandha') }}" class="bsw__card-link">
                                    Lihat Persyaratan
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
                <div class="bsw__empty-all rv">
                    <h3>Belum ada program beasiswa</h3>
                    <p>Informasi beasiswa akan tampil setelah diisi melalui panel superadmin.</p>
                </div>
            @endif
        @endif

    </div>
</section>
