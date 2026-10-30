@props(['title' => 'Dashboard'])

<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Panel superadmin pengelola konten {{ config('app.name') }}.">
    <meta name="theme-color" content="#1f5fbf">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — {{ config('app.name') }}</title>

    <x-dashboard.include.css />
    @stack('css')

</head>

<body>
    <div id="root" data-brand="{{ config('app.name') }}" data-logo="{{ asset('landing/assets/img/logo.webp') }}">
        <div class="boot">Memuat dashboard…</div>
    </div>

    <noscript>
        <p class="boot">Dashboard membutuhkan JavaScript.</p>
    </noscript>

    <x-dashboard.include.js />
    @stack('js')

</body>

</html>
