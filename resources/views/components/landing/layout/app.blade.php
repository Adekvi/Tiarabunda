@props([
    'page' => 'home',
    'ssr' => false,
    'title' => 'Universitas Tiara Bunda — Kampus Vokasi di Kota Depok',
    'description' =>
        'Universitas Tiara Bunda, kampus vokasi di Kota Depok dengan beragam fakultas dan program studi. Daftar PMB sekarang.',
])
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="theme-color" content="#2f6db5">

    <x-landing.part.css />
    {{-- tanpa JS, elemen .rv tetap terlihat --}}
    <noscript>
        <style>
            .rv {
                opacity: 1 !important;
                transform: none !important
            }
        </style>
    </noscript>
    @stack('css')
</head>

<body>
    <a class="skip" href="#main">Lewati ke konten utama</a>

    {{-- ssr=true: isi dirender Blade (slot), JS hanya menyalakan interaksi.
         ssr=false: kosong, dirender main.js sesuai data-page. --}}
    <div id="app" data-page="{{ $page }}" @if ($ssr) data-ssr @endif>
        {{ $slot }}</div>

    <x-landing.part.js />
    @stack('js')
</body>

</html>
