# CSS Partials Structure

File `style.css` adalah entry point. Semua partial di-`@import` di dalamnya.

## Struktur Folder

```
css/
├── style.css                 ← main (import semua)
└── partials/
    ├── _base.css             ← :root variables, reset, typography, buttons, tagline, utilities
    ├── _nav.css              ← topbar, navbar, dropdown nested (fakultas/prodi), search, burger
    ├── _sections.css         ← .sec, .alt, .head, .rv
    ├── _hero.css             ← hero section + hero-cta-card + has-media background
    ├── _mitra.css            ← partner/logo marquee
    ├── _info.css             ← full-screen info terkini slider
    ├── _market.css           ← profil kampus (market) section
    ├── _why.css              ← alasan / why choose us interactive
    ├── _legacy-components.css← .stat, .tabs, .grid, .gal, helper lama
    ├── _prodi.css            ← program studi (tabs + kartu prodi)
    ├── _fasilitas.css        ← fasilitas gallery slider
    ├── _berita.css           ← berita / news section
    ├── _faq.css              ← FAQ accordion
    ├── _cta.css              ← CTA section
    └── _footer.css           ← footer (lama .foot + baru .ft)
```

## Cara pakai di Laravel / Vite

Biasanya di `resources/css/app.css` atau langsung:

```css
@import "./style.css";
```

Atau jika memakai Vite:

```js
// resources/js/app.js
import '../css/style.css';
```

## Catatan

1. Urutan `@import` di `style.css` sudah disesuaikan agar cascade tetap benar.
2. Beberapa aturan responsive yang tersebar digabung ke partial terkait.
3. Jika ada konflik spesifisitas, partial yang di-import lebih akhir menang.
4. Kamu bisa mengedit partial sendiri-sendiri tanpa takut merusak yang lain.

## Mapping ke Blade Component

| Blade Component              | Partial terkait          |
|-----------------------------|--------------------------|
| layout.topbar + navbar      | `_nav.css`               |
| section.hero                | `_hero.css`              |
| section.info                | `_info.css`              |
| section.mitra               | `_mitra.css`             |
| section.market              | `_market.css`            |
| section.alasan              | `_why.css`               |
| section.statistik           | `_legacy-components.css` |
| section.prodi               | `_prodi.css`             |
| section.fasilitas           | `_fasilitas.css`         |
| section.berita              | `_berita.css`            |
| section.faq                 | `_faq.css`               |
| section.cta                 | `_cta.css`               |
| layout.footer               | `_footer.css`            |
