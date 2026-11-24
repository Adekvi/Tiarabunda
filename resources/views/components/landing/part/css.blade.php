<link href="{{ asset('landing/assets/img/logo.webp') }}" rel="icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

@php
    $stylePath = public_path('landing/assets/css/style.css');
    $styleVer = file_exists($stylePath) ? filemtime($stylePath) : time();
@endphp

<link rel="stylesheet" href="{{ asset('landing/assets/css/style.css') }}?v={{ $styleVer }}">
