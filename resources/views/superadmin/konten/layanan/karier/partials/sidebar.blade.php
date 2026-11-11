{{-- Statistik --}}
<div class="card krr-side-card">
    <h3 class="krr-side-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 3v18h18" />
            <path d="M7 15l4-6 4 4 4-8" />
        </svg>
        Statistik
    </h3>
    <div class="krr-stats">
        <div class="krr-stat">
            <span class="krr-stat__val">{{ $stats['total'] }}</span>
            <span class="krr-stat__lbl">Total</span>
        </div>
        <div class="krr-stat">
            <span class="krr-stat__val" style="color:var(--ok);">{{ $stats['aktif'] }}</span>
            <span class="krr-stat__lbl">Aktif</span>
        </div>
        <div class="krr-stat">
            <span class="krr-stat__val" style="color:var(--err);">{{ $stats['nonaktif'] }}</span>
            <span class="krr-stat__lbl">Nonaktif</span>
        </div>
        <div class="krr-stat">
            <span class="krr-stat__val" style="color:var(--pri);">{{ $stats['ada_tautan'] }}</span>
            <span class="krr-stat__lbl">Punya tautan</span>
        </div>
    </div>
</div>

{{-- Tentang halaman --}}
<div class="card krr-side-card">
    <h3 class="krr-side-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 16v-4" />
            <path d="M12 8h.01" />
        </svg>
        Tentang halaman
    </h3>
    <p class="krr-side-desc">
        Konten aktif tampil di <strong>Layanan → Pusat Karier</strong>.
        Cocok untuk profil unit, program magang, lowongan mitra, atau tautan job board.
    </p>
</div>

{{-- Saran konten --}}
<div class="card krr-side-card">
    <h3 class="krr-side-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M9 18h6" />
            <path d="M10 22h4" />
            <path d="M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.3 1 2.3h6c0-1 .4-1.8 1-2.3A7 7 0 0 0 12 2z" />
        </svg>
        Saran konten
    </h3>
    <ul class="krr-tips">
        <li>Profil Pusat Karier / CDC</li>
        <li>Program magang &amp; penempatan</li>
        <li>Lowongan mitra + tautan daftar</li>
    </ul>
</div>
