@props([
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
    {{ $slot }}
    <x-landing.part.js />
    @stack('js')
</body>

</html>
