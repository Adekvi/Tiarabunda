@props(['title' => 'Dashboard'])

@php
    $user = auth()->user();
    $initial = strtoupper(mb_substr($user->name ?? 'A', 0, 1));
@endphp

<header class="top">
    <button type="button" class="ib burger" data-sidebar-open aria-label="Buka menu">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div class="ttl">
        <p class="crumb">Superadmin<span>/</span>{{ $title }}</p>
        <h1>{{ $title }}</h1>
    </div>

    <label class="find">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M11 4a7 7 0 100 14 7 7 0 000-14zM21 21l-5-5" />
        </svg>
        <input type="search" placeholder="Cari…" aria-label="Cari">
    </label>

    <button type="button" class="ib" data-theme-toggle aria-label="Ganti tema" title="Ganti tema">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 8a4 4 0 100 8 4 4 0 000-8zM12 2v2M12 20v2M2 12h2M20 12h2" />
        </svg>
    </button>

    {{-- Akun (paling kanan) --}}
    <div class="account-wrap" data-account-menu style="position: relative;">
        <button type="button" class="account-btn" data-account-toggle aria-expanded="false" aria-haspopup="true"
            style="display: flex; align-items: center; gap: 10px; padding: 4px 8px 4px 4px; border-radius: 999px; border: 1px solid var(--line); background: var(--card);">
            <span class="av" style="width: 32px; height: 32px; font-size: 12px;">{{ $initial }}</span>
            <span style="text-align: left; line-height: 1.25; display: none;" class="account-meta">
                <b style="display: block; font-size: 12px;">{{ $user->name ?? 'Admin' }}</b>
                <small style="color: var(--mut); font-size: 10px;">{{ $user->role ?? 'admin' }}</small>
            </span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <path d="M6 9l6 6 6-6" />
            </svg>
        </button>

        <div class="account-dropdown" data-account-panel hidden
            style="position: absolute; right: 0; top: calc(100% + 8px); min-width: 220px; background: var(--card); border: 1px solid var(--line); border-radius: 12px; box-shadow: var(--sh); padding: 8px; z-index: 40;">
            <div style="padding: 10px 12px 12px; border-bottom: 1px solid var(--line); margin-bottom: 6px;">
                <b style="display: block; font-size: 13px;">{{ $user->name ?? 'Admin' }}</b>
                <small style="color: var(--mut);">{{ $user->email ?? '' }}</small>
            </div>
            <a href="#"
                style="display: block; padding: 10px 12px; border-radius: 8px; font-size: 13px; font-weight: 600;"
                onmouseover="this.style.background='var(--hov)'" onmouseout="this.style.background='transparent'">
                Profil & akun
            </a>
            <a href="#"
                style="display: block; padding: 10px 12px; border-radius: 8px; font-size: 13px; font-weight: 600;"
                onmouseover="this.style.background='var(--hov)'" onmouseout="this.style.background='transparent'">
                Pengaturan
            </a>
            <button type="button" data-logout-open
                style="width: 100%; text-align: left; padding: 10px 12px; border-radius: 8px; font-size: 13px; font-weight: 700; color: var(--err); margin-top: 4px;">
                Keluar
            </button>
        </div>
    </div>
</header>
