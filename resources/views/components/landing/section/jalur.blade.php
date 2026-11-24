@props(['items' => []])

@if (count($items) > 0)
    <section class="sec jalur-sec bg-left-lines" id="jalur" aria-labelledby="jalur-title">
        <div class="jalur-bg" aria-hidden="true">
            <div class="jalur-bg-dots"></div>
            <div class="jalur-bg-glow"></div>
        </div>

        <div class="wrap position-relative">
            <div class="jalur-layout rv">
                <div class="jalur-side">
                    <x-landing.ui.head kick="Beasiswa &amp; jalur" title="Program beasiswa yang tersedia"
                        lead="Dapatkan keringanan maupun bantuan biaya pendidikan melalui skema beasiswa resmi yang aktif untuk calon mahasiswa baru."
                        id="jalur-title" />

                    <div class="jalur-side-box">
                        <div class="jalur-side-stat">
                            <span class="jalur-side-num" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="6" />
                                    <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11" />
                                </svg>
                            </span>
                            <div class="jalur-side-copy">
                                <strong>Program Beasiswa Aktif</strong>
                                <span>Tersedia untuk calon mahasiswa baru</span>
                            </div>
                        </div>

                        <a href="/kemahasiswaan/beasiswa" class="jalur-side-btn">
                            <span>Info &amp; syarat beasiswa</span>
                            <x-landing.ui.arrow />
                        </a>
                    </div>
                </div>

                {{-- Kolom Kanan: Kartu Beasiswa --}}
                <div class="jalur-cards">
                    @foreach ($items as $j)
                        @php
                            $nomor = str_pad($loop->iteration, 2, '0', STR_PAD_LEFT);
                            $detailUrl =
                                $j['url'] ??
                                (!empty($j['tandha'])
                                    ? '/layanan/kemahasiswaan/beasiswa/' . $j['tandha']
                                    : '/layanan/kemahasiswaan/beasiswa');
                        @endphp
                        <article class="jalur-card">

                            <div class="jalur-card__top">
                                <div class="jalur-card__icon" aria-hidden="true">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                        <path d="M6 12v5c3 3 9 3 12 0v-5" />
                                    </svg>
                                </div>
                                <span class="jalur-card__tag">
                                    {{ !empty($j['cakupan']) ? $j['cakupan'] : 'Program ' . $nomor }}
                                </span>
                            </div>

                            <h3>{{ $j['nama'] }}</h3>

                            <p>{{ Str::limit(strip_tags($j['deskripsi']), 120) }}</p>

                            <a class="more" href="{{ $detailUrl }}" aria-label="Lihat detail {{ $j['nama'] }}">
                                Lihat detail
                                <x-landing.ui.arrow />
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
