@php
    $total = $stats['total'] ?? $items->total();
    $aktif = $stats['aktif'] ?? null;
    $terhapus = $stats['terhapus'] ?? 0;
@endphp

<aside class="prodi-aside">
    <div class="prodi-stat">
        <div class="prodi-stat__head">
            <span class="prodi-stat__label">Total prodi</span>
            @if ($aktif !== null)
                <span class="prodi-stat__badge">{{ $aktif }} aktif</span>
            @else
                <span class="prodi-stat__badge">{{ $items->total() }} di halaman</span>
            @endif
        </div>
        <div class="prodi-stat__value">{{ $total }}</div>
        <p class="prodi-stat__hint">
            @if ($stats ?? false)
                Ringkasan seluruh data (bukan hanya halaman ini).
            @else
                Jumlah sesuai filter & pagination saat ini.
            @endif
        </p>
    </div>

    <div class="prodi-aside-card">
        <h3 class="prodi-aside-card__title">Tentang Program Studi</h3>
        <p class="prodi-aside-card__text">
            Setiap prodi wajib terhubung ke satu fakultas. Data <strong>aktif</strong> tampil di
            <strong>Pendidikan → Program Pendidikan</strong>.
        </p>
    </div>

    <div class="prodi-aside-card">
        <h3 class="prodi-aside-card__title">Urutan kerja</h3>
        <ol class="prodi-steps">
            <li>Pastikan fakultas sudah dibuat</li>
            <li>Tambah program studi + jenjang</li>
            <li>Isi status akreditasi bila ada</li>
            <li>Aktifkan untuk ditampilkan di landing</li>
        </ol>
    </div>

    <div class="prodi-aside-card prodi-aside-card--action">
        <a href="{{ route('superadmin.prodi.create') }}" class="prodi-quick">
            <span class="prodi-quick__ico">+</span>
            <span>
                <strong>Tambah prodi</strong>
                <small>Buat program studi baru</small>
            </span>
        </a>
        <a href="{{ route('superadmin.fakultas.index') }}" class="prodi-quick">
            <span class="prodi-quick__ico prodi-quick__ico--mut">F</span>
            <span>
                <strong>Kelola fakultas</strong>
                <small>Unit induk prodi</small>
            </span>
        </a>
        @if ($terhapus > 0)
            <a href="{{ route('superadmin.prodi.trashed') }}" class="prodi-quick">
                <span class="prodi-quick__ico prodi-quick__ico--mut">{{ $terhapus }}</span>
                <span>
                    <strong>Sampah</strong>
                    <small>{{ $terhapus }} item menunggu</small>
                </span>
            </a>
        @endif
    </div>
</aside>
