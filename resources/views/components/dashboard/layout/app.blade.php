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

    {{-- Modal logout --}}
    <div class="modal-backdrop" data-logout-modal hidden
        style="position: fixed; inset: 0; z-index: 80; display: none; align-items: center; justify-content: center; background: rgba(10,18,32,.55); padding: 20px;">
        <div class="card" role="dialog" aria-modal="true" aria-labelledby="logoutTitle"
            style="width: min(400px, 100%); padding: 24px; box-shadow: 0 20px 50px rgba(0,0,0,.2);">
            <h2 id="logoutTitle" style="margin: 0 0 8px; font-size: 1.15rem;">Keluar dari panel?</h2>
            <p style="margin: 0 0 20px; color: var(--mut); font-size: 13px;">
                Sesi Anda akan diakhiri. Anda perlu masuk lagi untuk mengelola konten.
            </p>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn" data-logout-cancel
                    style="background: transparent; border: 1px solid var(--line); color: var(--ink);">
                    Batal
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn" style="background: var(--err);">
                        Ya, keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <x-dashboard.include.js />
    @stack('js')

</body>

</html>
