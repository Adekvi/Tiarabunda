@props(['items' => []])

@if (count($items))
    <section class="sec fasilitas-sec bg-glow" id="fasilitas">
        <div class="wrap">
            <x-landing.ui.head kick="Fasilitas" title="Ruang belajar yang mendukung praktik"
                lead="Laboratorium, studio, dan ruang belajar dirancang agar mahasiswa siap praktik sejak hari pertama." />
        </div>

        <div class="fas-gal rv" aria-label="Galeri fasilitas kampus">
            <div class="fas-track">
                @foreach ([false, true] as $dup)
                    @foreach ($items as $f)
                        <a href="{{ url('/layanan/fasilitas/' . ($f['tandha'] ?? Str::slug($f['nama'] ?? ''))) }}"
                            class="fas-card" @if ($dup) aria-hidden="true" tabindex="-1" @endif>
                            <div class="fas-card-media">
                                @if (!empty($f['img']))
                                    <img src="{{ $f['img'] }}" alt="{{ $dup ? '' : $f['nama'] ?? 'Fasilitas' }}"
                                        width="640" height="420" loading="lazy" decoding="async"
                                        onerror="this.parentElement.classList.add('is-empty')">
                                @else
                                    <div class="fas-placeholder" aria-hidden="true"></div>
                                @endif
                                <div class="fas-overlay"></div>
                            </div>

                            <div class="fas-card-body">
                                <span class="fas-label">Fasilitas</span>
                                <h3 class="fas-title">{{ $f['nama'] ?? 'Fasilitas Kampus' }}</h3>
                                <span class="fas-link">
                                    Lihat detail
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M5 12h14M13 6l6 6-6 6" />
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                @endforeach
            </div>
        </div>

        <div class="wrap fas-cta">
            <a href="/layanan/fasilitas" class="fas-btn">
                <span>Jelajahi semua fasilitas</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </a>
        </div>
    </section>
@endif
