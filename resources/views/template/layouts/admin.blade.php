<!doctype html>
<html lang="id" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Tiara Bunda Admin</title>
    <meta name="description" content="Panel administrasi Politeknik Tiara Bunda.">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script>
        try {
            document.documentElement.dataset.theme = localStorage.getItem('ptb-theme') || 'light'
        } catch (e) {}
    </script>
</head>

<body>
    <div class="app-shell" id="appShell">
        <div class="sidebar-backdrop" data-sidebar-close></div>
        <aside class="sidebar" id="sidebar">
            <a class="brand" href="{{ route('superadmin.dashboard') }}">
                <span class="brand-mark">TB</span>
                <span class="brand-copy"><strong>TIARA BUNDA</strong><small>Superadmin Panel</small></span>
            </a>
            <div class="sidebar-label">MENU UTAMA</div>
            <nav class="nav-menu" aria-label="Navigasi utama">
                <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}"
                    href="{{ route('superadmin.dashboard') }}"><span class="nav-icon">▦</span>Dashboard</a>
                <button class="nav-link nav-toggle {{ request()->is('superadmin/konten*') ? 'active' : '' }}"
                    data-dropdown-toggle="contentMenu"
                    aria-expanded="{{ request()->is('superadmin/konten*') ? 'true' : 'false' }}"><span
                        class="nav-icon">▤</span>Manajemen Konten <span class="chevron">⌄</span></button>
                <div class="nav-submenu {{ request()->is('superadmin/konten*') ? 'open' : '' }}" id="contentMenu">
                    <a href="{{ route('superadmin.content') }}">Semua Konten</a>
                    <a href="{{ route('superadmin.content') }}#hero">Hero & Banner</a>
                    <a href="{{ route('superadmin.content') }}#berita">Berita & Artikel</a>
                    <a href="{{ route('superadmin.content') }}#program">Program Studi</a>
                </div>
                <button class="nav-link nav-toggle" data-dropdown-toggle="pmbMenu" aria-expanded="false"><span
                        class="nav-icon">♙</span>Penerimaan Mahasiswa <span class="chevron">⌄</span></button>
                <div class="nav-submenu" id="pmbMenu">
                    <a href="{{ route('superadmin.admissions') }}">Data Pendaftar</a>
                    <a href="{{ route('superadmin.admissions') }}#program">Program PMB</a>
                    <a href="{{ route('superadmin.admissions') }}#pengumuman">Pengumuman</a>
                </div>
                <a class="nav-link {{ request()->routeIs('superadmin.calendar') ? 'active' : '' }}"
                    href="{{ route('superadmin.calendar') }}"><span class="nav-icon">▦</span>Kalender Akademik</a>
                <a class="nav-link {{ request()->routeIs('superadmin.gallery') ? 'active' : '' }}"
                    href="{{ route('superadmin.gallery') }}"><span class="nav-icon">▧</span>Galeri</a>
                <a class="nav-link {{ request()->routeIs('superadmin.faq') ? 'active' : '' }}"
                    href="{{ route('superadmin.faq') }}"><span class="nav-icon">?</span>FAQ & Bantuan</a>
                <div class="sidebar-label">PENGELOLAAN</div>
                <a class="nav-link {{ request()->routeIs('superadmin.users') ? 'active' : '' }}"
                    href="{{ route('superadmin.users') }}"><span class="nav-icon">♧</span>Pengguna & Role</a>
                <a class="nav-link {{ request()->routeIs('superadmin.settings') ? 'active' : '' }}"
                    href="{{ route('superadmin.settings') }}"><span class="nav-icon">⚙</span>Pengaturan</a>
            </nav>
            <div class="sidebar-bottom">
                <div class="support-card"><span class="support-icon">✦</span><strong>Butuh bantuan?</strong>
                    <p>Kelola website kampus dengan mudah.</p><a href="{{ route('superadmin.faq') }}">Pusat bantuan
                        →</a>
                </div>
                <div class="user-mini"><span class="avatar">SA</span><span class="user-mini-copy"><strong>Super
                            Admin</strong><small>Administrator</small></span><button class="icon-button"
                        aria-label="Menu akun" data-dropdown-toggle="accountMenu">•••</button></div>
                <div class="nav-submenu account-menu" id="accountMenu"><a
                        href="{{ route('superadmin.settings') }}">Profil & Pengaturan</a><a
                        href="{{ route('admin.sign-in') }}">Keluar (demo)</a></div>
            </div>
        </aside>

        <main class="main-area">
            <header class="topbar">
                <div class="topbar-left">
                    <button class="icon-button mobile-menu" data-sidebar-open aria-label="Buka navigasi">☰</button>
                    <div class="breadcrumb"><span>Tiara Bunda</span><span>/</span><strong>@yield('page-title', 'Dashboard')</strong>
                    </div>
                </div>
                <div class="topbar-actions">
                    <label class="search-box"><span>⌕</span><input type="search" placeholder="Cari menu atau data..."
                            aria-label="Cari" data-global-search></label>
                    <button class="icon-button theme-toggle" data-theme-toggle aria-label="Ganti tema"
                        title="Ganti tema">☾</button>
                    <button class="notification-button" aria-label="Notifikasi">♧<i></i></button>
                    <span class="avatar avatar-top">SA</span>
                </div>
            </header>
            <div class="page-content @yield('content-class')">
                @yield('content')
            </div>
            <footer class="footer"><span>© {{ date('Y') }} Politeknik Tiara Bunda</span><span>Superadmin Panel
                    <b>•</b> v1.0</span></footer>
        </main>
    </div>
    <script src="{{ asset('js/admin.js') }}" defer></script>
    @stack('scripts')
</body>

</html>
