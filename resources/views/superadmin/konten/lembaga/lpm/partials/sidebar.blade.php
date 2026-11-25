<aside class="lpm-side" aria-label="Informasi LPM">
    <div class="card lpm-side-card">
        <h3 class="lpm-side-title">Ringkasan Mutu (LPM)</h3>
        <div class="lpm-stat-grid">
            <div class="lpm-stat-box">
                <small>Total Konten</small>
                <b>{{ $stats['total'] ?? 0 }}</b>
            </div>
            <div class="lpm-stat-box">
                <small>Publik / Aktif</small>
                <b style="color: #15803d;">{{ $stats['aktif'] ?? 0 }}</b>
            </div>
            <div class="lpm-stat-box">
                <small>Dokumen SPMI</small>
                <b style="color: #1f5fbf;">{{ $stats['spmi'] ?? 0 }}</b>
            </div>
            <div class="lpm-stat-box">
                <small>Data Akreditasi</small>
                <b style="color: #15803d;">{{ $stats['akreditasi'] ?? 0 }}</b>
            </div>
        </div>
    </div>

    <div class="card lpm-side-card">
        <h3 class="lpm-side-title">Pilar Penjaminan Mutu</h3>
        <ul class="lpm-cat-list">
            @foreach ($categoryCounts as $key => $info)
                <li>
                    <a href="{{ route('superadmin.lpm.index', ['kategori' => $key]) }}"
                        style="color: var(--ink); text-decoration: none; font-weight: 600;">
                        {{ $info['label'] }}
                    </a>
                    <span class="lpm-badge {{ $key }}">{{ $info['jumlah'] }}</span>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="card lpm-side-card">
        <h3 class="lpm-side-title">Informasi Halaman Publik</h3>
        <ul class="lpm-guide">
            <li><b>Halaman Tujuan:</b> <code>/lembaga/lpm</code></li>
            <li><b>Siklus PPEPP:</b> Dokumen SPMI dikelompokkan otomatis berdasarkan tahapan Penetapan, Pelaksanaan,
                Evaluasi, Pengendalian, dan Peningkatan.</li>
            <li><b>Status Akreditasi:</b> Menampilkan peringkat akreditasi institusi maupun program studi beserta masa
                berlaku SK.</li>
        </ul>
    </div>
</aside>
