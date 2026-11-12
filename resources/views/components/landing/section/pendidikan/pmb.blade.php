@props(['pmbJalur' => [], 'selectedJalur' => null])

@php
    $items = collect($pmbJalur);
    $fmt = fn($d) => $d ? \Carbon\Carbon::parse($d)->translatedFormat('d M Y') : null;
@endphp

<section class="pmb">
    <div class="wrap pmb__wrap">

        {{-- ========================================== --}}
        {{-- JIKA JALUR DIPILIH (Halaman Detail Jalur) --}}
        {{-- ========================================== --}}
        @if ($selectedJalur)
            <div class="pmb__intro rv">
                <div class="pmb__back-wrap">
                    <a href="/pendidikan/penerimaan-mahasiswa-baru" class="pmb__back-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Daftar Jalur
                    </a>
                </div>

                <p class="pmb__eyebrow">Detail Jalur Pendaftaran</p>
                <h2 class="pmb__title">{{ data_get($selectedJalur, 'jeneng') }}</h2>

                <div class="pmb__meta">
                    @if (data_get($selectedJalur, 'status'))
                        <span class="pmb__status pmb__status--{{ data_get($selectedJalur, 'status') }}">
                            {{ data_get($selectedJalur, 'status_label', ucfirst(data_get($selectedJalur, 'status'))) }}
                        </span>
                    @endif

                    @if (data_get($selectedJalur, 'wektu_bukak') || data_get($selectedJalur, 'wektu_tutup'))
                        <div class="pmb__date-wrapper">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                            @if (data_get($selectedJalur, 'wektu_bukak'))
                                <span>Buka: <b>{{ $fmt(data_get($selectedJalur, 'wektu_bukak')) }}</b></span>
                            @endif
                            @if (data_get($selectedJalur, 'wektu_tutup'))
                                <span class="pmb__date-sep">-</span>
                                <span>Tutup: <b>{{ $fmt(data_get($selectedJalur, 'wektu_tutup')) }}</b></span>
                            @endif
                        </div>
                    @endif
                </div>

                @if (data_get($selectedJalur, 'katrangan'))
                    <div class="pmb__lead">
                        {{-- Gunakan {!! !!} jika konten dari database mengandung tag HTML (seperti <strong>) --}}
                        {!! nl2br(data_get($selectedJalur, 'katrangan')) !!}
                    </div>
                @endif
            </div>

            <div class="pmb__detail-grid">
                {{-- Syarat --}}
                <div class="pmb__panel rv">
                    <h3 class="pmb__panel-title">Persyaratan</h3>
                    @php $syarat = collect(data_get($selectedJalur, 'syarat', [])); @endphp
                    @if ($syarat->isNotEmpty())
                        <ul class="pmb__list">
                            @foreach ($syarat as $s)
                                <li>
                                    <span class="pmb__check" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                            stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12l4 4L19 6" />
                                        </svg>
                                    </span>
                                    <span>{{ $s }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="pmb__empty">Belum ada persyaratan yang ditambahkan.</p>
                    @endif
                </div>

                {{-- Langkah --}}
                <div class="pmb__panel rv">
                    <h3 class="pmb__panel-title">Alur Pendaftaran</h3>
                    @php $langkah = collect(data_get($selectedJalur, 'langkah', [])); @endphp
                    @if ($langkah->isNotEmpty())
                        <ol class="pmb__steps">
                            @foreach ($langkah as $i => $step)
                                <li>
                                    <span class="pmb__step-num">{{ $i + 1 }}</span>
                                    <span class="pmb__step-text">{{ $step }}</span>
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <p class="pmb__empty">Belum ada alur pendaftaran yang ditambahkan.</p>
                    @endif
                </div>
            </div>

            {{-- CTA --}}
            <div class="pmb__cta rv">
                <div>
                    <h3>Sudah siap mendaftar?</h3>
                    <p>Buat akun di portal PMB dan ikuti jalur ini sekarang.</p>
                </div>
                <a class="btn" href="#daftar">Daftar Sekarang &rarr;</a>
            </div>

            {{-- ========================================== --}}
            {{-- JIKA TIDAK ADA JALUR DIPILIH (Daftar Jalur) --}}
            {{-- ========================================== --}}
        @else
            <div class="pmb__intro rv">
                <p class="pmb__eyebrow">Penerimaan Mahasiswa Baru</p>
                <h2 class="pmb__title">Jalur pendaftaran mahasiswa baru</h2>
                <p class="pmb__lead">
                    Pilih jalur pendaftaran yang sesuai dengan profil Anda. Setiap jalur menampilkan status periode,
                    persyaratan, dan alur pendaftaran.
                </p>
            </div>

            @if ($items->isNotEmpty())
                <div class="pmb__grid">
                    @foreach ($items as $j)
                        <article class="pmb__card rv">
                            <div class="pmb__card-header">
                                <h3 class="pmb__card-title">{{ data_get($j, 'jeneng') }}</h3>
                                @if (data_get($j, 'status'))
                                    <span class="pmb__status pmb__status--{{ data_get($j, 'status') }}">
                                        {{ data_get($j, 'status_label', ucfirst(data_get($j, 'status'))) }}
                                    </span>
                                @endif
                            </div>

                            @if (data_get($j, 'katrangan'))
                                {{-- PERBAIKAN: Gunakan {!! !!} agar tag <strong> dari DB dirender --}}
                                <div class="pmb__card-desc">
                                    {!! data_get($j, 'katrangan') !!}
                                </div>
                            @endif

                            @if (data_get($j, 'wektu_bukak') || data_get($j, 'wektu_tutup'))
                                <div class="pmb__card-dates">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                    <span>
                                        {{ $fmt(data_get($j, 'wektu_bukak')) ?? '-' }} s/d
                                        {{ $fmt(data_get($j, 'wektu_tutup')) ?? '-' }}
                                    </span>
                                </div>
                            @endif

                            <div class="pmb__card-footer">
                                <a href="/pendidikan/penerimaan-mahasiswa-baru/{{ data_get($j, 'tandha') }}"
                                    class="pmb__card-link">
                                    Lihat Detail Pendaftaran
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
                <div class="pmb__empty-all rv">
                    <h3>Belum ada jalur pendaftaran</h3>
                    <p>Jalur PMB akan tampil setelah diisi melalui panel superadmin.</p>
                </div>
            @endif
        @endif

    </div>
</section>
