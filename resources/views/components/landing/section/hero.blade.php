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
            'btn1_link' => '/pendidikan/penerimaan-mahasiswa-baru',
            'btn2_teks' => 'Lihat program studi',
            'btn2_link' => '/pendidikan/program-pendidikan',
            'tipe' => 'none',
            'media_url' => null,
            'poster_url' => null,
        ],
        $data,
    );

    $hasMedia = in_array($d['tipe'], ['video', 'gambar']) && !empty($d['media_url']);
@endphp

<section class="hero {{ $hasMedia ? 'has-media' : '' }}" id="top">

    @if ($hasMedia)
        @if ($d['tipe'] === 'video')
            <video class="hero-bg" autoplay loop muted playsinline preload="auto" webkit-playsinline
                @if ($d['poster_url']) poster="{{ $d['poster_url'] }}" @endif>
                <source src="{{ $d['media_url'] }}" type="video/mp4">
            </video>
        @else
            <div class="hero-bg" style="background-image: url('{{ $d['media_url'] }}');"></div>
        @endif
        <div class="hero-overlay"></div>
    @endif

    <div class="wrap hero-in">
        <div class="hero-t {{ $hasMedia ? 'text-light' : '' }}">
            <p class="tick" aria-live="off">
                <span class="dot"></span>
                <span id="tick" data-items="{{ json_encode($ticker) }}">{{ $ticker[0] }}</span>
            </p>

            <h1>{{ $d['judul'] }}</h1>

            @if (!empty($d['sub_judul']))
                <p class="lead">{{ $d['sub_judul'] }}</p>
            @endif

            <div class="row">
                <a class="btn" href="https://forms.gle/eDmoP7SdmJyS1YxD6" target="_blank">{{ $d['btn1_teks'] }}</a>
                <a class="btn ghost {{ $hasMedia ? 'w' : '' }}" href="{{ $d['btn2_link'] }}">
                    {{ $d['btn2_teks'] }} <x-landing.ui.arrow />
                </a>
            </div>
        </div>

        <div class="hero-action-group">

            <a href="https://poltektiarabunda.pmbonline.siakad.tech/register" target="_blank" rel="noopener noreferrer"
                class="hero-cta-card hero-cta-card--primary">
                <div class="hero-cta__icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
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
                <div class="hero-cta__arrow">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
            </a>

            <a href="https://forms.gle/eDmoP7SdmJyS1YxD6" target="_blank" rel="noopener noreferrer"
                class="hero-cta-card hero-cta-card--secondary">
                <div class="hero-cta__icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div class="hero-cta__text">
                    <h2>Isi Formulir Bantuan</h2>
                    <p>Layanan Google Form</p>
                </div>
                <div class="hero-cta__arrow">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
            </a>

        </div>

    </div>
</section>
