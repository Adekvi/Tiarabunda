@props(['site' => config('landing.site'), 'ticker' => config('landing.home.ticker'), 'data' => []])

@php
    $d = array_merge(
        [
            'judul' => 'Kuliah yang langsung membawamu ke dunia kerja',
            'sub_judul' => '',
            'btn1_teks' => 'Daftar sekarang',
            'btn1_link' => '#pmb',
            'btn2_teks' => 'Lihat program studi',
            'btn2_link' => '#prodi',
            'tipe' => 'none',
            'media_url' => null,
            'poster_url' => null,
        ],
        $data,
    );
@endphp

<section class="hero {{ $d['tipe'] !== 'none' ? 'has-media' : '' }}" id="top">

    {{-- BACKGROUND MEDIA (VIDEO ATAU GAMBAR) --}}
    @if ($d['tipe'] === 'video' && $d['media_url'])
        <video class="hero-bg" autoplay loop muted playsinline preload="metadata" poster="{{ $d['poster_url'] }}">
            <source src="{{ $d['media_url'] }}" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>
    @elseif($d['tipe'] === 'gambar' && $d['media_url'])
        <div class="hero-bg" style="background-image: url('{{ $d['media_url'] }}');"></div>
        <div class="hero-overlay"></div>
    @endif

    <div class="wrap hero-in">
        <div class="hero-t {{ $d['tipe'] !== 'none' ? 'text-light' : '' }}">
            <p class="tick" aria-live="off">
                <span class="dot"></span>
                <span id="tick" data-items="{{ json_encode($ticker) }}">{{ $ticker[0] }}</span>
            </p>
            <h1>{{ $d['judul'] }}</h1>
            <p class="lead">{{ $d['sub_judul'] }}</p>
            <div class="row">
                <a class="btn" href="{{ $d['btn1_link'] }}">{{ $d['btn1_teks'] }}</a>
                <a class="btn ghost {{ $d['tipe'] !== 'none' ? 'w' : '' }}" href="{{ $d['btn2_link'] }}">
                    {{ $d['btn2_teks'] }} <x-landing.ui.arrow />
                </a>
            </div>
        </div>

        {{-- Visual hanya muncul jika tidak ada media background --}}
        @if ($d['tipe'] === 'none')
            <div class="hero-v" aria-hidden="true">
                <div class="arch">
                    <div class="sun"></div>
                    <div class="hill"></div>
                </div>
                <span class="chip c1">Praktik di laboratorium</span>
                <span class="chip c2">Magang industri</span>
                <span class="chip c3">Kelas pagi &amp; sore</span>
            </div>
        @endif

        <form class="pmb" id="pmb" novalidate>
            <h2>Mulai pendaftaran</h2>
            <p>Masukkan email aktif untuk membuat akun PMB.</p>
            <label for="em" class="sr">Email</label>
            <input id="em" type="email" placeholder="nama@email.com" autocomplete="email" required>
            <button class="btn" type="submit">Buat akun PMB</button>
            <p class="msg" id="msg" role="status"></p>
        </form>
    </div>
</section>
