<aside class="aka-side" aria-label="Informasi Layanan Akademik">
    <div class="card aka-side-card">
        <h3 class="aka-side-title">Ringkasan Layanan Akademik</h3>
        <div class="aka-stat-grid">
            <div class="aka-stat-box">
                <small>Total Dokumen</small>
                <b>{{ $stats['total'] ?? 0 }}</b>
            </div>
            <div class="aka-stat-box">
                <small>Publik / Aktif</small>
                <b style="color: #15803d;">{{ $stats['aktif'] ?? 0 }}</b>
            </div>
            <div class="aka-stat-box">
                <small>Draft / Nonaktif</small>
                <b style="color: var(--mut);">{{ $stats['nonaktif'] ?? 0 }}</b>
            </div>
            <div class="aka-stat-box">
                <small>Kategori</small>
                <b>{{ $stats['total_kategori'] ?? 0 }}</b>
            </div>
        </div>
    </div>

    <div class="card aka-side-card">
        <h3 class="aka-side-title">Distribusi per Kategori</h3>
        @if (!empty($categoryCounts))
            <ul class="aka-cat-list">
                @foreach ($categoryCounts as $namaKategori => $jumlah)
                    <li>
                        <a href="{{ route('superadmin.akademik.index', ['kategori' => $namaKategori]) }}"
                            style="color: var(--ink); text-decoration: none; font-weight: 600;">
                            {{ $namaKategori }}
                        </a>
                        <span class="aka-badge">{{ $jumlah }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p style="margin: 0; font-size: 12.5px; color: var(--mut);">Belum ada kategori dokumen aktif.</p>
        @endif
    </div>

    <div class="card aka-side-card">
        <h3 class="aka-side-title">Informasi Halaman Publik</h3>
        <ul class="aka-guide">
            <li><b>Halaman Tujuan:</b> <code>/layanan/akademik</code></li>
            <li><b>Route Unduhan:</b> <code>/layanan/akademik/unduh/{tandha}</code></li>
            <li><b>Sinkronisasi Cache:</b> Otomatis dibersihkan setiap kali dokumen ditambah, diubah, di-toggle, atau
                dihapus.</li>
            <li><b>Format Didukung:</b> PDF, Word, Excel, PowerPoint, dan ZIP (maks. 10 MB).</li>
        </ul>
    </div>
</aside>
