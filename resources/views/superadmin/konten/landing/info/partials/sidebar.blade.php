{{-- Statistik --}}
<div class="card info-side-card">
    <h3 class="info-side-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 3v18h18" />
            <path d="M7 15l4-6 4 4 4-8" />
        </svg>
        Ringkasan
    </h3>
    <div class="info-stats">
        <div class="info-stat">
            <span class="info-stat__val">{{ $stats['total'] }}</span>
            <span class="info-stat__lbl">Total</span>
        </div>
        <div class="info-stat is-ok">
            <span class="info-stat__val">{{ $stats['active'] }}</span>
            <span class="info-stat__lbl">Aktif</span>
        </div>
        <div class="info-stat is-err">
            <span class="info-stat__val">{{ $stats['draft'] }}</span>
            <span class="info-stat__lbl">Draf</span>
        </div>
        <div class="info-stat is-pri">
            <span class="info-stat__val">{{ $stats['categories'] }}</span>
            <span class="info-stat__lbl">Kategori</span>
        </div>
    </div>
</div>

{{-- Distribusi Kategori --}}
<div class="card info-side-card">
    <h3 class="info-side-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
            <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
        </svg>
        Distribusi Kategori
    </h3>
    @if ($categories->isEmpty())
        <p class="info-side-desc">Belum ada data.</p>
    @else
        <div class="info-dist-list">
            @foreach ($categories as $cat)
                <div class="info-dist-item">
                    <div class="info-dist-meta">
                        <span class="info-dist-name">{{ $cat->k ?: '—' }}</span>
                        <span class="info-dist-count">{{ $cat->total }}</span>
                    </div>
                    <div class="info-dist-track">
                        <div class="info-dist-fill" style="width: {{ ($cat->total / $maxCat) * 100 }}%;"></div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Panduan --}}
<div class="card info-side-card">
    <h3 class="info-side-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 16v-4" />
            <path d="M12 8h.01" />
        </svg>
        Panduan Singkat
    </h3>
    <ul class="info-tips">
        <li>Gunakan poster portrait (rasio ±5:7).</li>
        <li>Hanya status <strong>Aktif</strong> yang tampil di landing.</li>
        <li>Data yang dihapus masuk ke Sampah.</li>
    </ul>
</div>
