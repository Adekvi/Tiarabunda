<div class="prp-aside">
    {{-- Card Statistik Utama --}}
    <div class="card prp-stat">
        <div class="prp-stat__head">
            <h3>Ringkasan Data</h3>
            <p>Status konten perpustakaan saat ini.</p>
        </div>

        <div class="prp-stat__total">
            <div>
                <span class="prp-stat__val">{{ $stats['total'] }}</span>
                <span class="prp-stat__lbl">Total Blok Konten</span>
            </div>
            @php
                $persenAktif = $stats['total'] > 0 ? round(($stats['aktif'] / $stats['total']) * 100) : 0;
            @endphp
            <span class="prp-stat__pill"
                style="{{ $persenAktif == 100 ? 'background: var(--ok-t); color: var(--ok);' : '' }}">
                {{ $persenAktif }}% Aktif
            </span>
        </div>

        <div class="prp-stat__bar">
            <span class="prp-stat__bar-ok" style="width: {{ $persenAktif }}%"></span>
            <span class="prp-stat__bar-off" style="width: {{ 100 - $persenAktif }}%"></span>
        </div>

        <div class="prp-stat__legend">
            <span><i class="d-ok"></i> Aktif <b>{{ $stats['aktif'] }}</b></span>
            <span><i class="d-off"></i> Nonaktif <b>{{ $stats['nonaktif'] }}</b></span>
        </div>

        <div class="prp-stat__sep"></div>

        <h4 class="prp-stat__title">Rincian Tambahan</h4>
        <ul class="prp-stat__list">
            <li class="is-open">
                <div class="prp-stat__ico">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                    </svg>
                </div>
                <div class="prp-stat__meta">
                    <span class="prp-stat__name">Punya URL Portal</span>
                    <span class="prp-stat__hint">Tautan eksternal (OPAC/e-library)</span>
                </div>
                <span class="prp-stat__num">{{ $stats['ada_portal'] }}</span>
                <div class="prp-stat__mini">
                    <i
                        style="width: {{ $stats['total'] > 0 ? ($stats['ada_portal'] / $stats['total']) * 100 : 0 }}%"></i>
                </div>
            </li>
        </ul>
    </div>

    {{-- Card Informasi --}}
    <div class="card">
        <h3 class="prp-aside__title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            Tentang Halaman
        </h3>
        <p style="margin:0;font-size:0.85rem;color:var(--mut);line-height:1.65;">
            Konten aktif akan ditampilkan di <strong>Layanan → Perpustakaan</strong> pada landing page.
            Pastikan URL portal menggunakan format lengkap (<code>https://...</code>) agar tautan berfungsi.
        </p>
    </div>

    {{-- Card Tips --}}
    <div class="card">
        <h3 class="prp-aside__title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18h6"></path>
                <path d="M10 22h4"></path>
                <path
                    d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1.45.55 2.67 1.5 3.5.76.76 1.23 1.52 1.41 2.5">
                </path>
            </svg>
            Saran Pengisian
        </h3>
        <ul class="prp-tips">
            <li>Buat satu blok <strong>Profil Perpustakaan</strong> sebagai informasi utama.</li>
            <li>Gunakan blok tambahan untuk Jam Layanan, Peraturan, atau Koleksi.</li>
            <li>Isi kolom URL jika perpustakaan memiliki sistem mandiri.</li>
        </ul>
    </div>
</div>
