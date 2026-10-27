@props(['site' => config('landing.site'), 'ticker' => config('landing.home.ticker')])
<section class="hero" id="top">
    <div class="wrap hero-in">
        <div class="hero-t">
            <p class="tick" aria-live="off"><span class="dot"></span><span id="tick"
                    data-items="{{ json_encode($ticker) }}">{{ $ticker[0] }}</span></p>
            <h1>Kuliah vokasi yang langsung membawamu ke dunia kerja</h1>
            <p class="lead">{{ $site['nama'] }} menyiapkan lulusan terampil lewat praktik, magang, dan pendampingan
                karier di jantung {{ $site['kota'] }}.</p>
            <div class="row"><a class="btn" href="#pmb">Daftar sekarang</a><a class="btn ghost"
                    href="#prodi">Lihat program studi <x-landing.ui.arrow /></a></div>
        </div>
        <div class="hero-v" aria-hidden="true">
            <div class="arch">
                <div class="sun"></div>
                <div class="hill"></div>
            </div>
            <span class="chip c1">Praktik di laboratorium</span><span class="chip c2">Magang industri</span><span
                class="chip c3">Kelas pagi &amp; sore</span>
        </div>
        <form class="pmb" id="pmb" novalidate>
            <h2>Mulai pendaftaran</h2>
            <p>Masukkan email aktif untuk membuat akun PMB.</p>
            <label for="em" class="sr">Email</label><input id="em" type="email"
                placeholder="nama@email.com" autocomplete="email" required>
            <button class="btn" type="submit">Buat akun PMB</button>
            <p class="msg" id="msg" role="status"></p>
        </form>
    </div>
</section>
