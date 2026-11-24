@props([
    'site' => config('landing.site'),
    'ticker' => config('landing.home.ticker'),
    'data' => [],
])

@php
    $ticker = is_array($ticker) && !empty($ticker) ? array_values($ticker) : ['Penerimaan Mahasiswa Baru Dibuka'];
    $d = array_merge(
        [
            'judul' => 'Kuliah yang langsung membawamu ke dunia kerja',
            'sub_judul' =>
                $site['nama'] . ' menyiapkan lulusan terampil lewat praktik, magang, dan pendampingan karier.',
            'btn1_teks' => 'Daftar sekarang',
            'btn1_link' => 'https://forms.gle/eDmoP7SdmJyS1YxD6',
            'btn2_teks' => 'Lihat program studi',
            'btn2_link' => '/pendidikan/program-pendidikan',
            'tipe' => 'none',
            'media_url' => null,
            'poster_url' => null,
            'has_video' => false,
            'slides' => [],
            'interval' => 6000,
        ],
        $data,
    );

    // Normalisasi slides agar mendukung array 'slides' maupun format lama
    $rawSlides =
        !empty($d['slides']) && is_array($d['slides'])
            ? $d['slides']
            : (in_array($d['tipe'], ['video', 'gambar']) && !empty($d['media_url'])
                ? [['tipe' => $d['tipe'], 'media_url' => $d['media_url'], 'poster_url' => $d['poster_url'] ?? null]]
                : []);

    $slides = array_values(
        array_filter($rawSlides, function ($item) {
            return is_array($item) && in_array($item['tipe'] ?? '', ['video', 'gambar']) && !empty($item['media_url']);
        }),
    );

    $hasMedia = count($slides) > 0;
    $hasSlider = count($slides) > 1;
    $hasVideo = $hasMedia && $slides[0]['tipe'] === 'video';
    $interval = max(5000, min(7000, (int) ($d['interval'] ?? 6000)));
    $isBtn1External = str_starts_with($d['btn1_link'], 'http');
@endphp

<section class="hero {{ $hasMedia ? 'has-media' : '' }} {{ $hasSlider ? 'has-slider' : '' }}" id="top"
    @if ($hasSlider) data-hero-slider
        data-has-video="{{ $hasVideo ? '1' : '0' }}"
        data-interval="{{ $interval }}"
        style="--hero-interval: {{ $interval }}ms;" @endif>
    @if ($hasMedia)
        <div class="hero-slider" aria-hidden="true">
            @foreach ($slides as $idx => $slide)
                <div class="hero-slide {{ $idx === 0 ? 'is-active' : '' }}" data-hero-slide="{{ $idx }}">
                    @if ($slide['tipe'] === 'video')
                        <video class="hero-bg" @if ($idx === 0) autoplay @endif loop muted playsinline
                            preload="{{ $idx === 0 ? 'auto' : 'metadata' }}"
                            @if (!empty($slide['poster_url'])) poster="{{ $slide['poster_url'] }}" @endif>
                            <source src="{{ $slide['media_url'] }}" type="video/mp4">
                        </video>
                    @else
                        <div class="hero-bg" style="background-image: url('{{ $slide['media_url'] }}');"></div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="hero-overlay" aria-hidden="true"></div>
    @endif

    @if ($hasSlider)
        {{-- Tombol Slider Ujung Paling Kiri --}}
        <button type="button" class="hero-edge-nav hero-edge-nav--prev" data-hero-prev
            aria-label="Tampilan sebelumnya">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>

        {{-- Tombol Slider Ujung Paling Kanan --}}
        <button type="button" class="hero-edge-nav hero-edge-nav--next" data-hero-next
            aria-label="Tampilan berikutnya">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>
    @endif

    <div class="wrap hero-in">
        <div class="hero-t {{ $hasMedia ? 'text-light' : '' }}">
            <p class="tick" aria-live="off">
                <span class="dot" aria-hidden="true"></span>
                <span id="tick" data-items="{{ json_encode($ticker) }}">{{ $ticker[0] }}</span>
            </p>

            <h1>{{ $d['judul'] }}</h1>

            @if (!empty($d['sub_judul']))
                <p class="lead">{{ $d['sub_judul'] }}</p>
            @endif

            <div class="hero-btns">
                <a class="btn" href="{{ $d['btn1_link'] }}"
                    @if ($isBtn1External) target="_blank" rel="noopener noreferrer" @endif>
                    {{ $d['btn1_teks'] }}
                </a>
                <a class="btn ghost {{ $hasMedia ? 'w' : '' }}" href="{{ $d['btn2_link'] }}">
                    {{ $d['btn2_teks'] }} <x-landing.ui.arrow />
                </a>
            </div>
        </div>

        <div class="hero-action-group">
            <a href="https://poltektiarabunda.pmbonline.siakad.tech/register" target="_blank" rel="noopener noreferrer"
                class="hero-cta-card hero-cta-card--primary">
                <div class="hero-cta__icon" aria-hidden="true">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                </div>
                <div class="hero-cta__text">
                    <h2>Buat Akun Pendaftaran</h2>
                    <p>Sistem PMB Terintegrasi</p>
                </div>
                <div class="hero-cta__arrow" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
            </a>

            <a href="https://forms.gle/eDmoP7SdmJyS1YxD6" target="_blank" rel="noopener noreferrer"
                class="hero-cta-card hero-cta-card--secondary">
                <div class="hero-cta__icon" aria-hidden="true">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div class="hero-cta__text">
                    <h2>Link Pendaftaran</h2>
                    <p>Google Form</p>
                </div>
                <div class="hero-cta__arrow" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    @if ($hasSlider)
        <div class="hero-slider-controls" aria-label="Indikator tampilan hero">
            <div class="hero-slider-dots" role="tablist" aria-label="Pilih tampilan">
                @foreach ($slides as $idx => $slide)
                    <button type="button" class="hero-slider-dot {{ $idx === 0 ? 'is-active' : '' }}"
                        data-hero-dot="{{ $idx }}"
                        aria-label="{{ $slide['tipe'] === 'video' ? 'Video Utama' : 'Foto ' . ($hasVideo ? $idx : $idx + 1) }}"
                        @if ($idx === 0) aria-current="true" @endif>
                        <span></span>
                    </button>
                @endforeach
            </div>
        </div>
    @endif
</section>
