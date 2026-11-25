<aside class="kjs-side" aria-label="Informasi Layanan Kerjasama">
    <div class="card kjs-side-card">
        <h3 class="kjs-side-title">Ringkasan Kerjasama</h3>
        <div class="kjs-stat-grid">
            <div class="kjs-stat-box">
                <small>Mitra Instansi</small>
                <b style="color: #1f5fbf;">{{ $stats['mitra'] ?? 0 }}</b>
            </div>
            <div class="kjs-stat-box">
                <small>Skema Penawaran</small>
                <b style="color: #b45309;">{{ $stats['penawaran'] ?? 0 }}</b>
            </div>
            <div class="kjs-stat-box">
                <small>Publik / Aktif</small>
                <b style="color: #15803d;">{{ $stats['aktif'] ?? 0 }}</b>
            </div>
            <div class="kjs-stat-box">
                <small>Draft / Nonaktif</small>
                <b style="color: var(--mut);">{{ $stats['nonaktif'] ?? 0 }}</b>
            </div>
        </div>
    </div>

    <div class="card kjs-side-card">
        <h3 class="kjs-side-title">Distribusi Sektor Mitra</h3>
        @if (!empty($categoryCounts))
            <ul class="kjs-cat-list">
                @foreach ($categoryCounts as $namaKategori => $jumlah)
                    <li>
                        <a href="{{ route('superadmin.kerjasama.index', ['kategori' => $namaKategori]) }}"
                            style="color: var(--ink); text-decoration: none; font-weight: 600;">
                            {{ $namaKategori }}
                        </a>
                        <span class="kjs-badge">{{ $jumlah }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p style="margin: 0; font-size: 12.5px; color: var(--mut);">Belum ada sektor kerjasama aktif.</p>
        @endif
    </div>

    <div class="card kjs-side-card">
        <h3 class="kjs-side-title">Informasi Halaman Publik</h3>
        <ul class="kjs-guide">
            <li><b>Halaman Publik:</b> <code>/layanan/kerjasama</code></li>
            <li><b>Mitra Berjalan:</b> Menampilkan logo instansi, sektor industri/kesehatan, serta ruang lingkup MoU.
            </li>
            <li><b>Penawaran Kerjasama:</b> Menampilkan skema kolaborasi (magang, penelitian, praktisi mengajar) beserta
                unduhan proposal dan kontak PIC.</li>
        </ul>
    </div>
</aside>
