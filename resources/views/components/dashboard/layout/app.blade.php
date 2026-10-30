@props([
    'title' => 'Dashboard',
    'pageTitle' => null,
])

@php
    $pageTitle = $pageTitle ?? $title;
    $user = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1f5fbf">
    <title>{{ $title }} — {{ config('app.name') }}</title>
    <x-dashboard.include.css />
    @stack('css')
</head>

<body>
    <div class="app" id="dashboardApp" data-brand="{{ config('app.name') }}"
        data-logo="{{ asset('landing/assets/img/logo.webp') }}">

        <x-dashboard.include.sidebar :user="$user" />

        <div class="dim" data-sidebar-close hidden></div>

        <div class="main">
            <x-dashboard.include.topbar :title="$pageTitle" />

            <main class="page">
                {{ $slot }}
            </main>
        </div>
    </div>

    <x-dashboard.include.js />
    @stack('js')
</body>

</html>
