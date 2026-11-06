@php
    $total = (int) ($stats['total'] ?? 0);
    $pct = fn($n) => $total > 0 ? (int) round(($n / $total) * 100) : 0;
    $pAktif = $pct($stats['aktif'] ?? 0);
    $pNon = $total > 0 ? 100 - $pAktif : 0;

    // Hitung sisa data yang belum ada gambarnya
    $tanpaGambar = max(0, ($stats['aktif'] ?? 0) - ($stats['ada_gambar'] ?? 0));

    $statusRows = [
        [
            'lbl' => 'Institusi',
            'hint' => 'Akreditasi tingkat universitas',
            'val' => $stats['institusi'] ?? 0,
            'cls' => 'is-open',
            'icon' => 'M3 21h18M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16M9 9h6M9 13h6M9 17h6',
        ],
        [
            'lbl' => 'Program Studi',
            'hint' => 'Akreditasi per program studi',
            'val' => $stats['prodi'] ?? 0,
            'cls' => 'is-soon',
            'icon' => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 1 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15z',
        ],
        [
            'lbl' => 'Belum Ada Gambar',
            'hint' => 'Sertifikat yang perlu diunggah',
            'val' => $tanpaGambar,
            'cls' => 'is-wait',
            'icon' => 'M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12',
        ],
    ];
@endphp

<aside class="akr-aside">
    {{-- Kartu Statistik Utama --}}
    <div class="card akr-stat">
        <div class="akr-stat__head">
            <h3>Statistik Akreditasi</h3>
            <p>Ringkasan seluruh data akreditasi</p>
        </div>

        <div class="akr-stat__total">
            <div>
                <span class="akr-stat__val">{{ number_format($total, 0, ',', '.') }}</span>
                <span class="akr-stat__lbl">Total data</span>
            </div>
            @if ($total > 0)
                <span class="akr-stat__pill">{{ $pAktif }}% aktif</span>
            @endif
        </div>

        <div class="akr-stat__bar" role="img"
            aria-label="{{ $stats['aktif'] ?? 0 }} data aktif dan {{ $stats['nonaktif'] ?? 0 }} nonaktif dari {{ $total }} data">
            <span class="akr-stat__bar-ok" style="width:{{ $pAktif }}%"></span>
            <span class="akr-stat__bar-off" style="width:{{ $pNon }}%"></span>
        </div>

        <div class="akr-stat__legend">
            <span><i class="d-ok"></i>Aktif <b>{{ $stats['aktif'] ?? 0 }}</b></span>
            <span><i class="d-off"></i>Nonaktif <b>{{ $stats['nonaktif'] ?? 0 }}</b></span>
        </div>

        <div class="akr-stat__sep"></div>

        <p class="akr-stat__title">Rincian Data</p>
        <ul class="akr-stat__list">
            @foreach ($statusRows as $r)
                <li class="{{ $r['cls'] }}">
                    <span class="akr-stat__ico" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="{{ $r['icon'] }}" />
                        </svg>
                    </span>
                    <span class="akr-stat__meta">
                        <span class="akr-stat__name">{{ $r['lbl'] }}</span>
                        <span class="akr-stat__hint">{{ $r['hint'] }}</span>
                    </span>
                    <b class="akr-stat__num">{{ $r['val'] }}</b>
                    <span class="akr-stat__mini" title="{{ $pct($r['val']) }}% dari total data">
                        <i style="width:{{ $pct($r['val']) }}%"></i>
                    </span>
                </li>
            @endforeach
        </ul>

        {{-- Notifikasi Sampah --}}
        @if (($stats['terhapus'] ?? 0) > 0)
            <div class="akr-stat__alert" role="status">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3l10 18H2zM12 10v5M12 18h.01" />
                </svg>
                <span>Ada <strong>{{ $stats['terhapus'] }} data</strong> di sampah. Segera pulihkan atau hapus
                    permanen.</span>
            </div>
        @endif

        <p class="akr-stat__note">
            Dihitung dari seluruh data, bukan hanya halaman ini. Diperbarui setiap muat ulang.
        </p>
    </div>

    {{-- Panduan --}}
    <div class="card">
        <h3 style="margin-bottom:10px;">Saran pengisian</h3>
        <ul class="akr-tips">
            <li>Gunakan gambar scan sertifikat asli (JPG/PNG).</li>
            <li>Pastikan status akreditasi diisi dengan benar (misal: "Baik Sekali", "A", "Unggul").</li>
            <li>Pilih "Prodi" dan tentukan nama program studinya jika akreditasi tersebut khusus untuk prodi.</li>
            <li>Gunakan Urutan untuk menaruh akreditasi institusi di posisi teratas.</li>
        </ul>
    </div>
</aside>
