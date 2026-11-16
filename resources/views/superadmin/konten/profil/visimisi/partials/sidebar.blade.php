@php
    $total = (int) ($stats['total'] ?? 0);
    $aktif = (int) ($stats['aktif'] ?? 0);
    $nonaktif = (int) ($stats['nonaktif'] ?? 0);
    $visi = (int) ($stats['visi'] ?? 0);
    $misi = (int) ($stats['misi'] ?? 0);
    $terhapus = (int) ($stats['terhapus'] ?? 0);
    $pAktif = $total > 0 ? (int) round(($aktif / $total) * 100) : 0;
    $pNon = $total > 0 ? 100 - $pAktif : 0;
@endphp

<aside class="vis-aside">
    <div class="card vis-stat">
        <div class="vis-stat__head">
            <h3>Statistik data</h3>
            <p>Ringkasan seluruh entri</p>
        </div>

        <div class="vis-stat__total">
            <div>
                <span class="vis-stat__val">{{ number_format($total, 0, ',', '.') }}</span>
                <span class="vis-stat__lbl">Total data</span>
            </div>
            @if ($total > 0)
                <span class="vis-stat__pill">{{ $pAktif }}% aktif</span>
            @endif
        </div>

        <div class="vis-stat__bar" role="img"
            aria-label="{{ $aktif }} data aktif dan {{ $nonaktif }} nonaktif dari {{ $total }}">
            <span class="vis-stat__bar-ok" style="width:{{ $pAktif }}%"></span>
            <span class="vis-stat__bar-off" style="width:{{ $pNon }}%"></span>
        </div>

        <div class="vis-stat__legend">
            <span><i class="d-ok"></i>Aktif <b>{{ $aktif }}</b></span>
            <span><i class="d-off"></i>Nonaktif <b>{{ $nonaktif }}</b></span>
        </div>

        <div class="vis-stat__sep"></div>

        <div class="vis-stat" style="display:grid;gap:10px;font-size:0.88rem;">
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--mut);">Visi</span>
                <strong>{{ $visi }}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--mut);">Misi</span>
                <strong>{{ $misi }}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--mut);">Di sampah</span>
                <strong>{{ $terhapus }}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--mut);">Halaman</span>
                <strong>{{ $items->currentPage() }} / {{ max($items->lastPage(), 1) }}</strong>
            </div>
        </div>

        <p class="vis-stat__note">
            Dihitung dari seluruh data, bukan hanya halaman ini.
        </p>
    </div>

    <div class="card">
        <h3 style="margin-bottom:10px;">Tentang halaman ini</h3>
        <p style="margin:0;font-size:0.88rem;color:var(--mut);line-height:1.65;">
            Tampil di <strong>Profil → Visi &amp; Misi</strong>.
            Disarankan: satu entri <strong>Visi</strong> (narasi) dan satu/lebih
            <strong>Misi</strong> (poin-poin).
        </p>
    </div>

    <div class="card">
        <h3 style="margin-bottom:10px;">Perbedaan Visi &amp; Misi</h3>
        <ul class="vis-tips">
            <li><strong>Visi</strong> — satu paragraf tujuan jangka panjang.</li>
            <li><strong>Misi</strong> — daftar langkah/komitmen (satu baris satu poin).</li>
            <li>Urutan kecil = tampil lebih dulu.</li>
            <li>Hanya data <strong>aktif</strong> yang muncul di landing.</li>
        </ul>
    </div>
</aside>
