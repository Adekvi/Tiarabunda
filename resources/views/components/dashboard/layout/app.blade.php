<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Superadmin — {{ config('app.name') }}</title>
    <meta name="description" content="Panel pengelola konten website {{ config('app.name') }}.">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#173f87">
    <link rel="icon" href="{{ asset('landing/assets/img/logo.webp') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    {{-- Terapkan tema sebelum render agar tidak berkedip --}}
    <script>
        try {
            var t = localStorage.getItem('sa.theme') || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' :
                'light');
            document.documentElement.classList.toggle('dark', t === 'dark');
        } catch (e) {}
    </script>

    @viteReactRefresh
    @vite(['resources/js/superadmin/main.jsx'])
    
</head>

<body>
    <div id="sa-root"></div>
    <noscript>Dashboard membutuhkan JavaScript untuk berjalan.</noscript>
</body>

</html>
