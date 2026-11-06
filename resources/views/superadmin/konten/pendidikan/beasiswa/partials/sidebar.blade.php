@php
    $total = (int) ($stats['total'] ?? 0);
    $pct = fn($n) => $total > 0 ? (int) round(($n / $total) * 100) : 0;
@endphp

<aside class="beasiswa-aside">
    {{-- Tentang --}}
    <div class="card beasiswa-summary">
        <div class="beasiswa-summary__head">
            <span class="beasiswa-badge is-new">Informasi</span>
            <h3>Tentang Beasiswa</h3>
            <p>Data aktif tampil di <strong>Pendidikan → Beasiswa</strong> pada situs publik. Cantumkan cakupan dan
                persyaratan agar calon penerima mudah memahami.</p>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="card beasiswa-stat">
        <div class="beasiswa-stat__head">
            <h3>Statistik</h3>
            <p>Ringkasan seluruh data</p>
        </div>

        <div class="beasiswa-stat__total">
            <div>
                <span class="beasiswa-stat__val">{{ number_format($total, 0, ',', '.') }}</span>
                <span class="beasiswa-stat__lbl">Total Beasiswa</span>
            </div>
            @if ($total > 0)
                <span class="beasiswa-stat__pill">{{ $pct($stats['aktif'] ?? 0) }}% Aktif</span>
            @endif
        </div>

        <div class="beasiswa-stat__list">
            <div class="beasiswa-stat__item">
                <span class="beasiswa-stat__dot is-ok"></span>
                <span class="beasiswa-stat__name">Aktif</span>
                <strong class="beasiswa-stat__num">{{ $stats['aktif'] ?? 0 }}</strong>
            </div>
            <div class="beasiswa-stat__item">
                <span class="beasiswa-stat__dot is-err"></span>
                <span class="beasiswa-stat__name">Nonaktif</span>
                <strong class="beasiswa-stat__num">{{ $stats['nonaktif'] ?? 0 }}</strong>
            </div>
            <div class="beasiswa-stat__item">
                <span class="beasiswa-stat__dot is-pri"></span>
                <span class="beasiswa-stat__name">Dengan Syarat</span>
                <strong class="beasiswa-stat__num">{{ $stats['dengan_syarat'] ?? 0 }}</strong>
            </div>
            <div class="beasiswa-stat__item">
                <span class="beasiswa-stat__dot is-acc"></span>
                <span class="beasiswa-stat__name">Cakupan Terisi</span>
                <strong class="beasiswa-stat__num">{{ $stats['dengan_cakupan'] ?? 0 }}</strong>
            </div>
        </div>

        <p class="beasiswa-stat__note">Dihitung dari seluruh data, diperbarui setiap muat ulang.</p>
    </div>

    {{-- Panduan --}}
    <div class="card">
        <h3 style="margin-bottom:10px;">Panduan</h3>
        <ul class="beasiswa-tips">
            <li>Satu skema = satu entri (KIP, prestasi, alumni, dll.).</li>
            <li>Cakupan: jelaskan biaya apa saja yang ditanggung.</li>
            <li>Persyaratan ditulis jelas, bisa multi-paragraf.</li>
        </ul>
    </div>
</aside>
