@php
    $total = (int) ($stats['total'] ?? 0);
    $aktif = (int) ($stats['aktif'] ?? 0);
    $nonaktif = (int) ($stats['nonaktif'] ?? 0);
    $terhapus = (int) ($stats['terhapus'] ?? 0);
    $pAktif = $total > 0 ? (int) round(($aktif / $total) * 100) : 0;
    $pNon = $total > 0 ? 100 - $pAktif : 0;
@endphp

<aside class="sej-aside">
    <div class="card sej-stat">
        <div class="sej-stat__head">
            <h3>Statistik Sejarah</h3>
            <p>Ringkasan seluruh konten</p>
        </div>

        <div class="sej-stat__total">
            <div>
                <span class="sej-stat__val">{{ number_format($total, 0, ',', '.') }}</span>
                <span class="sej-stat__lbl">Total konten</span>
            </div>
            @if ($total > 0)
                <span class="sej-stat__pill">{{ $pAktif }}% aktif</span>
            @endif
        </div>

        <div class="sej-stat__bar" role="img"
            aria-label="{{ $aktif }} konten aktif dan {{ $nonaktif }} nonaktif dari {{ $total }} konten">
            <span class="sej-stat__bar-ok" style="width:{{ $pAktif }}%"></span>
            <span class="sej-stat__bar-off" style="width:{{ $pNon }}%"></span>
        </div>

        <div class="sej-stat__legend">
            <span><i class="d-ok"></i>Aktif <b>{{ $aktif }}</b></span>
            <span><i class="d-off"></i>Nonaktif <b>{{ $nonaktif }}</b></span>
        </div>

        <div class="sej-stat__sep"></div>

        <div style="display:grid;gap:10px;font-size:0.88rem;">
            <div style="display:flex;justify-content:space-between;gap:8px;">
                <span style="color:var(--mut);">Halaman</span>
                <strong>{{ $items->currentPage() }} / {{ max($items->lastPage(), 1) }}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;gap:8px;">
                <span style="color:var(--mut);">Di sampah</span>
                <strong>{{ $terhapus }}</strong>
            </div>
        </div>

        <p class="sej-stat__note">
            Dihitung dari seluruh data, bukan hanya halaman ini.
        </p>
    </div>

    <div class="card">
        <h3 style="margin-bottom:10px;">Tentang halaman ini</h3>
        <p style="margin:0;font-size:0.88rem;color:var(--mut);line-height:1.65;">
            Konten di sini ditampilkan pada <strong>Profil → Sejarah</strong> di situs publik.
            Hanya data berstatus <strong>Aktif</strong> yang muncul di landing.
        </p>
    </div>

    <div class="card">
        <h3 style="margin-bottom:10px;">Saran penulisan</h3>
        <ul class="sej-tips">
            <li>Mulai dari latar berdirinya kampus.</li>
            <li>Sertakan tonggak penting (tahun, capaian).</li>
            <li>Gunakan bahasa formal, ringkas, dan mudah dibaca.</li>
            <li>Urutan kecil = tampil lebih dulu.</li>
        </ul>
    </div>
</aside>
