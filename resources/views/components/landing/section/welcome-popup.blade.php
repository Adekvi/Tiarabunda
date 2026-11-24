@props(['data' => []])

@php
    $slides = collect($data)->values();
@endphp

@if ($slides->isNotEmpty())
    <div class="wpop" id="welcome-popup" role="dialog" aria-modal="true" aria-labelledby="wpop-active-title" hidden>
        <div class="wpop__backdrop" data-wpop-close></div>

        <div class="wpop__dialog" role="document" data-wpop-slider>
            <button type="button" class="wpop__close" data-wpop-close aria-label="Tutup jendela informasi">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                    stroke-width="2.2" stroke-linecap="round">
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>

            <div class="wpop__topbar">
                <div class="wpop__dots" role="tablist" aria-label="Pilihan topik informasi">
                    @foreach ($slides as $i => $slide)
                        <button type="button" class="wpop__dot {{ $i === 0 ? 'is-active' : '' }}" role="tab"
                            aria-selected="{{ $i === 0 ? 'true' : 'false' }}" data-wpop-go="{{ $i }}">
                            <span class="wpop__dot-num">0{{ $i + 1 }}</span>
                            <span class="wpop__dot-lbl">{{ $slide['badge'] }}</span>
                        </button>
                    @endforeach
                </div>

                @if ($slides->count() > 1)
                    <div class="wpop__nav">
                        <button type="button" class="wpop__arrow" data-wpop-prev aria-label="Slide sebelumnya">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 18l-6-6 6-6" />
                            </svg>
                        </button>
                        <span class="wpop__counter" data-wpop-counter>1 / {{ $slides->count() }}</span>
                        <button type="button" class="wpop__arrow" data-wpop-next aria-label="Slide berikutnya">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18l6-6-6-6" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>

            <div class="wpop__viewport">
                <div class="wpop__track" data-wpop-track>
                    @foreach ($slides as $i => $slide)
                        @php
                            $kat = $slide['kategori'] ?? 'info';
                        @endphp
                        <article class="wpop__slide wpop__slide--{{ $kat }} {{ $i === 0 ? 'is-active' : '' }}"
                            data-wpop-slide="{{ $i }}" aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">

                            <div class="wpop__slide-grid {{ !empty($slide['gambar']) ? 'has-media' : '' }}">
                                <div class="wpop__slide-body">
                                    <span class="wpop__badge">{{ $slide['badge'] }}</span>

                                    <h2 class="wpop__title" id="{{ $i === 0 ? 'wpop-active-title' : '' }}">
                                        {{ $slide['judul'] }}
                                    </h2>

                                    @if (!empty($slide['deskripsi']))
                                        <p class="wpop__desc">{!! nl2br(e($slide['deskripsi'])) !!}</p>
                                    @endif

                                    <div class="wpop__actions">
                                        @if (!empty($slide['url_tombol']) && !empty($slide['teks_tombol']))
                                            <a href="{{ $slide['url_tombol'] }}" class="wpop__btn-primary"
                                                @if (!empty($slide['buka_tab_baru'])) target="_blank" rel="noopener noreferrer" @endif>
                                                <span>{{ $slide['teks_tombol'] }}</span>
                                                <span aria-hidden="true">&rarr;</span>
                                            </a>
                                        @endif

                                        @if (!empty($slide['url_sekunder']) && !empty($slide['teks_sekunder']))
                                            <a href="{{ $slide['url_sekunder'] }}" class="wpop__btn-ghost">
                                                {{ $slide['teks_sekunder'] }}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                @if (!empty($slide['gambar']))
                                    <div class="wpop__slide-media">
                                        <img src="{{ $slide['gambar'] }}" alt="{{ $slide['judul'] }}" loading="lazy"
                                            onerror="this.parentElement.style.display='none'">
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="wpop__foot">
                <span class="wpop__hint">Gunakan tombol panah kiri/kanan untuk melihat informasi lainnya</span>
                <button type="button" class="wpop__skip" data-wpop-close>
                    Lanjutkan ke Beranda
                </button>
            </div>
        </div>
    </div>
@endif
