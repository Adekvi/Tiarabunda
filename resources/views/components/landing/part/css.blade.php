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

<style>
    /* ============================================================
   NESTED DROPDOWN - PROGRAM PENDIDIKAN (CLICK TOGGLE)
   ============================================================ */

    /* Wrapper */
    .dd-nested {
        position: relative;
    }

    /* Toggle button (menggantikan <a> sebelumnya) */
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
        border-radius: 6px;
        transition: background 0.2s;
    }

    .dd-nested-toggle:hover {
        background: rgba(0, 0, 0, 0.04);
    }

    /* Panah indikator */
    .dd-nested-toggle .dd-arrow {
        display: inline-block;
        width: 8px;
        height: 8px;
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

    /* Panel sub-dropdown (default: tersembunyi) */
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

    /* Item prodi & header fakultas */
    .dd-fak-head {
        padding: 8px 12px 4px;
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--blue-d, #0b3d91);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--line, #e5e7eb);
        margin-bottom: 4px;
    }

    .dd-prodi {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 12px 6px 24px;
        font-size: 0.9rem;
        color: inherit;
        text-decoration: none;
        border-radius: 6px;
        transition: background 0.15s;
    }

    .dd-prodi:hover {
        background: rgba(0, 0, 0, 0.05);
    }

    .dd-badge {
        font-size: 0.7rem;
        background: var(--sky, #e0f2fe);
        padding: 2px 6px;
        border-radius: 4px;
        color: var(--blue, #1e40af);
    }

    .dd-empty {
        display: block;
        padding: 4px 24px;
        font-size: 0.85rem;
        color: var(--mut, #6b7280);
        font-style: italic;
    }

    /* ============================================================
   DESKTOP: Panel muncul sebagai flyout ke kanan saat di-open
   ============================================================ */
    @media (min-width: 961px) {
        .dd-nested-panel {
            position: absolute;
            top: 0;
            left: 100%;
            min-width: 280px;
            max-height: 80vh;
            overflow-y: auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
            padding: 8px;
            z-index: 20;
            margin-top: 0;
        }

        /* Panel sembunyi default di desktop */
        .dd-nested-panel {
            display: none;
        }

        .dd-nested.open>.dd-nested-panel {
            display: block;
        }
    }

    /* ============================================================
   MOBILE: Accordion style
   ============================================================ */
    @media (max-width: 960px) {
        .dd-nested-panel {
            position: static;
            box-shadow: none;
            border: 0;
            padding-left: 16px;
            margin-left: 12px;
            border-left: 2px solid var(--line, #e5e7eb);
            margin-top: 4px;
        }

        /* Scroll bisa panjang di mobile */
        .dd-nested.open>.dd-nested-panel {
            max-height: none;
        }
    }

    /* ============ Footer ============
   Atur warna lewat variabel di bawah (atau definisikan --footer-bg / --accent
   di :root style.css Anda). */
    .ft {
        --ft-bg: var(--footer-bg, #0b1d3a);
        --ft-bg-soft: rgba(255, 255, 255, 0.05);
        --ft-line: rgba(255, 255, 255, 0.12);
        --ft-text: rgba(255, 255, 255, 0.72);
        --ft-strong: #fff;
        --ft-accent: var(--accent, #f5b942);

        background: var(--ft-bg);
        color: var(--ft-text);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-top: 64px;
    }

    .ft *,
    .ft *::before,
    .ft *::after {
        box-sizing: border-box;
    }

    .ft ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .ft a {
        color: inherit;
        text-decoration: none;
        transition: color .2s ease;
    }

    .ft__wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }

    /* ---------- CTA ---------- */
    .ft__cta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        padding: 28px 32px;
        margin-top: -40px;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--ft-accent), #ffd98a);
        color: #1a1a1a;
        box-shadow: 0 16px 40px rgba(0, 0, 0, .25);
    }

    .ft__cta h3 {
        margin: 0 0 4px;
        font-size: 1.25rem;
        font-weight: 800;
        color: #14213d;
    }

    .ft__cta p {
        margin: 0;
        color: rgba(20, 33, 61, .8);
    }

    .ft__btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
        padding: 12px 22px;
        border-radius: 999px;
        background: #14213d;
        color: #fff !important;
        font-weight: 700;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .ft__btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(20, 33, 61, .35);
    }

    /* ---------- Grid ---------- */
    .ft__grid {
        display: grid;
        grid-template-columns: 1.6fr repeat(4, 1fr);
        gap: 40px 32px;
        padding: 56px 0 40px;
    }

    /* Brand */
    .ft__logo {
        display: inline-flex;
        align-items: center;
        gap: 14px;
    }

    .ft__logo img {
        border-radius: 12px;
        background: #fff;
        padding: 4px;
        object-fit: contain;
    }

    .ft__logo b {
        display: block;
        color: var(--ft-strong);
        font-size: 1.1rem;
        font-weight: 800;
        line-height: 1.2;
    }

    .ft__logo small {
        color: var(--ft-text);
        font-size: .8rem;
    }

    /* Kontak */
    .ft__contact {
        display: grid;
        gap: 12px;
        margin-top: 22px;
    }

    .ft__contact li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .ft__contact svg {
        flex: 0 0 18px;
        width: 18px;
        height: 18px;
        margin-top: 3px;
        fill: none;
        stroke: var(--ft-accent);
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .ft__contact a:hover {
        color: var(--ft-strong);
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    /* Sosial */
    .ft__sosial {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 22px;
    }

    .ft__sosial a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        border: 1px solid var(--ft-line);
        background: var(--ft-bg-soft);
        color: var(--ft-strong);
        transition: background .2s ease, color .2s ease, transform .2s ease, border-color .2s ease;
    }

    .ft__sosial a:hover {
        background: var(--ft-accent);
        border-color: var(--ft-accent);
        color: #14213d;
        transform: translateY(-3px);
    }

    .ft__sosial a:focus-visible,
    .ft__btn:focus-visible,
    .ft__col a:focus-visible {
        outline: 2px solid var(--ft-accent);
        outline-offset: 3px;
        border-radius: 6px;
    }

    /* Kolom menu */
    .ft__col h4 {
        position: relative;
        margin: 0 0 20px;
        padding-bottom: 12px;
        color: var(--ft-strong);
        font-size: .85rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .ft__col h4::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 28px;
        height: 3px;
        border-radius: 3px;
        background: var(--ft-accent);
    }

    .ft__col ul {
        display: grid;
        gap: 10px;
    }

    .ft__col a {
        display: inline-block;
        transition: color .2s ease, transform .2s ease;
    }

    .ft__col a:hover {
        color: var(--ft-strong);
        transform: translateX(4px);
    }

    /* ---------- Bar bawah ---------- */
    .ft__bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px 24px;
        padding: 22px 0 28px;
        border-top: 1px solid var(--ft-line);
        font-size: .85rem;
    }

    .ft__bottom p {
        margin: 0;
    }

    .ft__portal {
        display: flex;
        flex-wrap: wrap;
        gap: 6px 20px;
    }

    .ft__portal a:hover {
        color: var(--ft-accent);
    }

    /* ---------- Responsif ---------- */
    @media (max-width: 1024px) {
        .ft__grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .ft__brand {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 640px) {
        .ft__wrap {
            padding: 0 20px;
        }

        .ft__cta {
            flex-direction: column;
            align-items: flex-start;
            padding: 24px 22px;
        }

        .ft__btn {
            width: 100%;
            justify-content: center;
        }

        .ft__grid {
            grid-template-columns: 1fr 1fr;
            gap: 32px 20px;
            padding: 44px 0 32px;
        }

        .ft__bottom {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .ft * {
            transition: none !important;
        }
    }
</style>
