{{-- Tentang --}}
<div class="card kmh-side-card">
    <h3 class="kmh-side-title">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 16v-4" />
            <path d="M12 8h.01" />
        </svg>
        Tentang halaman ini
    </h3>
    <p class="kmh-side-desc">
        Konten aktif tampil di <strong>Layanan → Kemahasiswaan</strong>.
        Cocok untuk profil unit, UKM, beasiswa internal, atau pengumuman layanan mahasiswa.
    </p>
</div>

{{-- Statistik --}}
<div class="card kmh-side-card">
    <h3 class="kmh-side-title">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 3v18h18" />
            <path d="M7 14l4-4 4 3 5-7" />
        </svg>
        Statistik
    </h3>
    <div class="kmh-stats">
        <div class="kmh-stat">
            <span class="kmh-stat__val">{{ $stats['total'] }}</span>
            <span class="kmh-stat__lbl">Total</span>
        </div>
        <div class="kmh-stat">
            <span class="kmh-stat__val" style="color:var(--ok);">{{ $stats['aktif'] }}</span>
            <span class="kmh-stat__lbl">Aktif</span>
        </div>
        <div class="kmh-stat">
            <span class="kmh-stat__val" style="color:var(--err);">{{ $stats['nonaktif'] }}</span>
            <span class="kmh-stat__lbl">Nonaktif</span>
        </div>
        <div class="kmh-stat">
            <span class="kmh-stat__val" style="color:var(--pri);">{{ $stats['ada_kontak'] }}</span>
            <span class="kmh-stat__lbl">Kontak</span>
        </div>
    </div>
</div>

{{-- Saran konten --}}
<div class="card kmh-side-card">
    <h3 class="kmh-side-title">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 18h6" />
            <path d="M10 22h4" />
            <path
                d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1.45.55 2.67 1.5 3.5.76.76 1.23 1.52 1.41 2.5" />
        </svg>
        Saran konten
    </h3>
    <ul class="kmh-tips">
        <li>Profil Biro/Bagian Kemahasiswaan</li>
        <li>Daftar UKM / organisasi</li>
        <li>Layanan konseling atau kesejahteraan</li>
    </ul>
</div>
