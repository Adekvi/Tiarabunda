@php
    $total = (int) ($stats['total'] ?? 0);
    $pct = fn($n) => $total > 0 ? (int) round(($n / $total) * 100) : 0;
    $pAktif = $pct($stats['aktif'] ?? 0);
    $pNon = $total > 0 ? 100 - $pAktif : 0;

    $statusRows = [
        [
            'lbl' => 'Dengan Foto',
            'hint' => 'Profil pimpinan yang sudah memiliki foto',
            'val' => $stats['dengan_foto'] ?? 0,
            'cls' => 'is-open',
            'icon' => 'M4 4h16v16H4zM8 10a2 2 0 100-4 2 2 0 000 4zM14 14h4M14 18h4M4 16l3-3 4 4',
        ],
        [
            'lbl' => 'Belum Ada Foto',
            'hint' => 'Profil tanpa foto (placeholder inisial)',
            'val' =>
                ($stats['aktif'] ?? 0) - ($stats['dengan_foto'] ?? 0) > 0
                    ? ($stats['aktif'] ?? 0) - ($stats['dengan_foto'] ?? 0)
                    : 0,
            'cls' => 'is-wait',
            'icon' => 'M12 3a9 9 0 100 18 9 9 0 000-18zM8 10a2 2 0 104 0 2 2 0 00-4 0zM12 14c-1.33 0-2.6.45-3.6 1.2',
        ],
        [
            'lbl' => 'Di Sampah',
            'hint' => 'Data yang sudah dihapus sementara',
            'val' => $stats['terhapus'] ?? 0,
            'cls' => 'is-soon',
            'icon' => 'M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6',
        ],
    ];
@endphp

<aside class="pim-aside">
    {{-- Kartu Statistik --}}
    <div class="card pim-stat">
        <div class="pim-stat__head">
            <h3>Statistik Pimpinan</h3>
            <p>Ringkasan seluruh data pimpinan</p>
        </div>

        <div class="pim-stat__total">
            <div>
                <span class="pim-stat__val">{{ number_format($total, 0, ',', '.') }}</span>
                <span class="pim-stat__lbl">Total pimpinan</span>
            </div>
            @if ($total > 0)
                <span class="pim-stat__pill">{{ $pAktif }}% aktif</span>
            @endif
        </div>

        <div class="pim-stat__bar" role="img"
            aria-label="{{ $stats['aktif'] ?? 0 }} data aktif dan {{ $stats['nonaktif'] ?? 0 }} nonaktif dari {{ $total }} data">
            <span class="pim-stat__bar-ok" style="width:{{ $pAktif }}%"></span>
            <span class="pim-stat__bar-off" style="width:{{ $pNon }}%"></span>
        </div>

        <div class="pim-stat__legend">
            <span><i class="d-ok"></i>Aktif <b>{{ $stats['aktif'] ?? 0 }}</b></span>
            <span><i class="d-off"></i>Nonaktif <b>{{ $stats['nonaktif'] ?? 0 }}</b></span>
        </div>

        <div class="pim-stat__sep"></div>

        <p class="pim-stat__title">Kelengkapan Profil</p>
        <ul class="pim-stat__list">
            @foreach ($statusRows as $r)
                <li class="{{ $r['cls'] }}">
                    <span class="pim-stat__ico" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="{{ $r['icon'] }}" />
                        </svg>
                    </span>
                    <span class="pim-stat__meta">
                        <span class="pim-stat__name">{{ $r['lbl'] }}</span>
                        <span class="pim-stat__hint">{{ $r['hint'] }}</span>
                    </span>
                    <b class="pim-stat__num">{{ $r['val'] }}</b>
                    <span class="pim-stat__mini" title="{{ $pct($r['val']) }}% dari total data">
                        <i style="width:{{ $pct($r['val']) }}%"></i>
                    </span>
                </li>
            @endforeach
        </ul>

        @if (($stats['terhapus'] ?? 0) > 0)
            <div class="pim-stat__alert" role="status">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3l10 18H2zM12 10v5M12 18h.01" />
                </svg>
                <span>Ada <strong>{{ $stats['terhapus'] }} data</strong> di sampah. Segera pulihkan atau hapus
                    permanen.</span>
            </div>
        @endif

        <p class="pim-stat__note">
            Dihitung dari seluruh data, bukan hanya halaman ini. Diperbarui setiap muat ulang.
        </p>
    </div>

    {{-- Panduan --}}
    <div class="card">
        <h3 style="margin-bottom:10px;">Saran pengisian</h3>
        <ul class="pim-tips">
            <li>Gunakan foto formal dengan rasio 1:1 (persegi) agar tampil rapi.</li>
            <li>Isi jabatan secara lengkap (contoh: "Dekan Fakultas Ilmu Kesehatan").</li>
            <li>Unen (sambutan) disarankan tidak lebih dari 3 paragraf.</li>
            <li>Gunakan Urutan untuk mengatur hierarki (misal: Rektor = 1, Warek = 2, dst).</li>
        </ul>
    </div>
</aside>
