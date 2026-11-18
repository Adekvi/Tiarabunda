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

<style>
    /* ============================================================
   NESTED DROPDOWN - PROGRAM PENDIDIKAN (OPTIMIZED)
   ============================================================ */

    .dd-nested {
        position: relative;
    }

    .dd-nested-toggle {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 16px;
        background: transparent;
        border: 0;
        font: inherit;
        color: inherit;
        text-align: left;
        cursor: pointer;
        border-radius: 8px;
        transition: background 0.2s;
    }

    .dd-nested-toggle:hover {
        background: rgba(0, 0, 0, 0.04);
    }

    .dd-nested-toggle .dd-arrow {
        display: inline-block;
        width: 7px;
        height: 7px;
        border-right: 2px solid currentColor;
        border-bottom: 2px solid currentColor;
        transform: rotate(45deg);
        transition: transform 0.25s var(--ease, ease);
        margin-left: 8px;
        flex-shrink: 0;
    }

    .dd-nested.open>.dd-nested-toggle .dd-arrow {
        transform: rotate(-135deg);
    }

    /* Panel dasar */
    .dd-nested-panel {
        display: none;
        margin-top: 4px;
    }

    .dd-nested.open>.dd-nested-panel {
        display: block;
        animation: ddFadeIn 0.2s ease;
    }

    @keyframes ddFadeIn {
        from {
            opacity: 0;
            transform: translateY(-4px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Grup Fakultas */
    .dd-fakultas-group {
        padding: 4px 0 10px;
        margin-bottom: 6px;
    }

    .dd-fakultas-group:not(:last-child) {
        border-bottom: 1px dashed color-mix(in srgb, var(--line) 55%, transparent);
        padding-bottom: 14px;
        margin-bottom: 10px;
    }

    .dd-fak-head {
        display: flex;
        align-items: baseline;
        gap: 6px;
        padding: 6px 12px 4px;
        margin-bottom: 2px;
    }

    .dd-fak-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--mut);
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .dd-fak-name {
        font-size: 0.84rem;
        font-weight: 700;
        color: var(--blue-d);
        line-height: 1.3;
    }

    .dd-prodi-list {
        display: flex;
        flex-direction: column;
        gap: 1px;
        padding: 0 6px;
    }

    /* Item prodi */
    .dd-nested-panel .dd-prodi {
        display: flex !important;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding: 9px 12px 9px 14px !important;
        font-size: 0.9rem;
        color: var(--ink) !important;
        text-decoration: none;
        border-radius: 7px !important;
        background: transparent !important;
        transition: background 0.15s, color 0.15s;
    }

    .dd-nested-panel .dd-prodi:hover {
        background: color-mix(in srgb, var(--blue) 7%, transparent) !important;
        color: var(--blue) !important;
    }

    .dd-prodi-name {
        flex: 1;
        min-width: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dd-badge {
        flex: none;
        margin-left: 8px;
        font-size: 0.68rem;
        font-weight: 700;
        background: var(--sky);
        padding: 2px 7px;
        border-radius: 4px;
        color: var(--blue);
        letter-spacing: 0.02em;
    }

    .dd-empty {
        display: block;
        padding: 8px 14px;
        font-size: 0.85rem;
        color: var(--mut);
        font-style: italic;
    }

    /* ============================================================
   DESKTOP (≥ 961px) - Flyout + Scroll
   ============================================================ */
    @media (min-width: 961px) {
        .dd-p {
            overflow: visible;
            /* agar flyout tidak terpotong */
        }

        .dd-nested-panel {
            position: absolute;
            top: -8px;
            width: max-content;
            min-width: 280px;
            max-width: min(380px, calc(100vw - 48px));
            max-height: min(70vh, 520px);
            /* tinggi aman + bisa di-scroll */
            overflow-y: auto;
            overflow-x: hidden;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: 0 14px 36px -12px rgba(23, 59, 102, 0.22);
            padding: 10px 8px;
            z-index: 40;
            margin-top: 0;
            display: none;

            /* Default buka ke kanan */
            left: calc(100% + 6px);
            right: auto;

            /* Smooth scroll */
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        /* Flyout ke kiri jika ruang kanan tidak cukup */
        .dd-nested-panel.flyout-left {
            left: auto;
            right: calc(100% + 6px);
        }

        .dd-nested.open>.dd-nested-panel {
            display: block;
        }

        /* Scrollbar halus */
        .dd-nested-panel::-webkit-scrollbar {
            width: 5px;
        }

        .dd-nested-panel::-webkit-scrollbar-track {
            background: transparent;
            margin: 8px 0;
        }

        .dd-nested-panel::-webkit-scrollbar-thumb {
            background: #c5d0de;
            border-radius: 10px;
        }

        .dd-nested-panel::-webkit-scrollbar-thumb:hover {
            background: #a8b8cc;
        }
    }

    /* ============================================================
   TABLET & MOBILE (≤ 960px) - Accordion + Scroll
   ============================================================ */
    @media (max-width: 960px) {
        .dd-nested-panel {
            position: static !important;
            left: auto !important;
            right: auto !important;
            width: auto !important;
            min-width: 0 !important;
            max-width: 100% !important;
            max-height: 280px;
            /* biar tidak terlalu panjang */
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: none !important;
            border: 0 !important;
            border-radius: 0;
            padding: 6px 0 6px 12px !important;
            margin: 6px 0 0 10px !important;
            border-left: 2px solid var(--line) !important;
            background: transparent;
            -webkit-overflow-scrolling: touch;
        }

        .dd-nested.open>.dd-nested-panel {
            display: block;
        }

        .dd-prodi {
            padding: 10px 10px 10px 8px !important;
        }

        .dd-fak-head {
            padding: 8px 8px 4px;
        }

        /* Scrollbar lebih tipis di mobile */
        .dd-nested-panel::-webkit-scrollbar {
            width: 4px;
        }

        .dd-nested-panel::-webkit-scrollbar-thumb {
            background: #c5d0de;
            border-radius: 10px;
        }
    }

    /* Layar sangat kecil */
    @media (max-width: 480px) {
        .dd-nested-panel {
            max-height: 240px;
            margin-left: 6px !important;
            padding-left: 10px !important;
        }

        .dd-prodi-name {
            font-size: 0.88rem;
        }
    }
</style>
