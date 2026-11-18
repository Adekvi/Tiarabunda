<link href="{{ asset('landing/assets/img/logo.webp') }}" rel="icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">

<link rel="stylesheet"
    href="{{ asset('landing/assets/css/style.css') }}?v={{ filemtime(public_path('landing/assets/css/style.css')) }}">

<link rel="stylesheet" href="{{ asset('landing/assets/css/additions.css') }}">

{{-- Sejarah --}}
<link rel="stylesheet"
    href="{{ asset('landing/menu/profil/sejarah/sej.css') }}?v={{ @filemtime(public_path('landing/menu/profil/sejarah/sej.css')) ?: '1' }}">
{{-- Visi Misi --}}
<link rel="stylesheet"
    href="{{ asset('landing/menu/profil/visi/visi.css') }}?v={{ @filemtime(public_path('landing/menu/profil/visi/visi.css')) ?: '1' }}">
{{-- Pendidikan --}}
<link rel="stylesheet"
    href="{{ asset('landing/menu/pendidikan/program/prog.css') }}?v={{ @filemtime(public_path('landing/menu/pendidikan/program/prog.css')) ?: '1' }}">
{{-- PMB --}}
<link rel="stylesheet"
    href="{{ asset('landing/menu/pendidikan/pmb/pmb.css') }}?v={{ @filemtime(public_path('landing/menu/pendidikan/pmb/pmb.css')) ?: '1' }}">
{{-- Beasiswa --}}
<link rel="stylesheet"
    href="{{ asset('landing/menu/pendidikan/beasiswa/bea.css') }}?v={{ @filemtime(public_path('landing/menu/pendidikan/beasiswa/bea.css')) ?: '1' }}">
{{-- Beasiswa --}}
<link rel="stylesheet"
    href="{{ asset('landing/menu/pendidikan/akreditasi/akre.css') }}?v={{ @filemtime(public_path('landing/menu/pendidikan/akreditasi/akre.css')) ?: '1' }}">
{{-- Perpus --}}
<link rel="stylesheet"
    href="{{ asset('landing/menu/layanan/perpus/perpus.css') }}?v={{ @filemtime(public_path('landing/menu/layanan/perpus/perpus.css')) ?: '1' }}">
{{-- Kemahasiswaan --}}
<link rel="stylesheet"
    href="{{ asset('landing/menu/layanan/kemaha/kemaha.css') }}?v={{ @filemtime(public_path('landing/menu/layanan/kemaha/kemaha.css')) ?: '1' }}">
{{-- Pusat Karir --}}
<link rel="stylesheet"
    href="{{ asset('landing/menu/layanan/karier/karier.css') }}?v={{ @filemtime(public_path('landing/menu/layanan/karier/karier.css')) ?: '1' }}">
{{-- Fasilitas --}}
<link rel="stylesheet"
    href="{{ asset('landing/menu/layanan/fasilitas/fasi.css') }}?v={{ @filemtime(public_path('landing/menu/layanan/fasilitas/fasi.css')) ?: '1' }}">
{{-- Informasi --}}
<link rel="stylesheet"
    href="{{ asset('landing/menu/informasi/berita/berita.css') }}?v={{ @filemtime(public_path('landing/menu/informasi/berita/berita.css')) ?: '1' }}">
{{-- Kegiatan --}}
<link rel="stylesheet"
    href="{{ asset('landing/menu/informasi/kegiatan/kegiatan.css') }}?v={{ @filemtime(public_path('landing/menu/informasi/kegiatan/kegiatan.css')) ?: '1' }}">
{{-- Faq --}}
<link rel="stylesheet"
    href="{{ asset('landing/menu/informasi/faq/faq.css') }}?v={{ @filemtime(public_path('landing/menu/faq/faq/faq.css')) ?: '1' }}">