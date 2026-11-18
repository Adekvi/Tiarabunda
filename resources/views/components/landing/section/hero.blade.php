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

    {{-- BACKGROUND MEDIA --}}
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
        {{-- Teks Hero --}}
        {{-- PERUBAHAN: Tambahkan class text-light agar teks tetap putih dan terbaca di atas overlay --}}
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
                <a class="btn" href="{{ $d['btn1_link'] }}">{{ $d['btn1_teks'] }}</a>
                <a class="btn ghost {{ $hasMedia ? 'w' : '' }}" href="{{ $d['btn2_link'] }}">
                    {{ $d['btn2_teks'] }} <x-landing.ui.arrow />
                </a>
            </div>
        </div>

        {{-- Visual Animasi (TETAP MUNCUL, baik ada media maupun tidak) --}}
        {{-- <div class="hero-v" aria-hidden="true">
            <div class="arch">
                <div class="sun"></div>
                <div class="hill"></div>
            </div>
            <span class="chip c1">Praktik di laboratorium</span>
            <span class="chip c2">Magang industri</span>
            <span class="chip c3">Kelas pagi &amp; sore</span>
        </div> --}}

        <form class="hero-pmb" id="pmb" novalidate>
            <div class="hero-pmb__head">
                <h2>Mulai pendaftaran</h2>
                <p>Masukkan email aktif untuk membuat akun PMB.</p>
            </div>

            <div class="hero-pmb__row">
                <label for="em" class="sr">Email</label>
                <input id="em" type="email" placeholder="nama@email.com" autocomplete="email" required>
                <button class="btn" type="submit">Buat akun PMB</button>
            </div>

            <p class="hero-pmb__msg" id="msg" role="status"></p>
        </form>
    </div>
</section>
