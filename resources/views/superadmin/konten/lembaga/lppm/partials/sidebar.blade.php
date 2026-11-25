<aside class="lppm-side" aria-label="Informasi LPPM">
    <div class="card lppm-side-card">
        <h3 class="lppm-side-title">Ringkasan Konten LPPM</h3>
        <div class="lppm-stat-grid">
            <div class="lppm-stat-box">
                <small>Total Konten</small>
                <b>{{ $stats['total'] ?? 0 }}</b>
            </div>
            <div class="lppm-stat-box">
                <small>Publik / Aktif</small>
                <b style="color: #15803d;">{{ $stats['aktif'] ?? 0 }}</b>
            </div>
            <div class="lppm-stat-box">
                <small>Penelitian</small>
                <b style="color: #1f5fbf;">{{ $stats['penelitian'] ?? 0 }}</b>
            </div>
            <div class="lppm-stat-box">
                <small>Pengabdian</small>
                <b style="color: #15803d;">{{ $stats['pengabdian'] ?? 0 }}</b>
            </div>
        </div>
    </div>

    <div class="card lppm-side-card">
        <h3 class="lppm-side-title">Pilar &amp; Kategori LPPM</h3>
        <ul class="lppm-cat-list">
            @foreach ($categoryCounts as $key => $info)
                <li>
                    <a href="{{ route('superadmin.lppm.index', ['kategori' => $key]) }}"
                        style="color: var(--ink); text-decoration: none; font-weight: 600;">
                        {{ $info['label'] }}
                    </a>
                    <span class="lppm-badge {{ $key }}">{{ $info['jumlah'] }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="card lppm-side-card">
        <h3 class="lppm-side-title">Informasi Halaman Publik</h3>
        <ul class="lppm-guide">
            <li><b>Halaman Tujuan:</b> <code>/lembaga/lppm</code></li>
            <li><b>Struktur Konten:</b> Mendukung pengelompokan otomatis berdasarkan 5 pilar utama LPPM (Profil,
                Penelitian, Pengabdian, Publikasi, dan HKI).</li>
            <li><b>Sinkronisasi Cache:</b> Otomatis dibersihkan setiap kali entri LPPM disimpan, diubah, atau dihapus.
            </li>
        </ul>
    </div>
</aside>
