@php
    $total = (int) ($stats['total'] ?? 0);
    $pct = fn($n) => $total > 0 ? (int) round(($n / $total) * 100) : 0;
    $pAktif = $pct($stats['aktif'] ?? 0);
    $pNon = $total > 0 ? 100 - $pAktif : 0;

    $statusRows = [
        [
            'lbl' => 'Sedang dibuka',
            'hint' => 'Menerima pendaftar saat ini',
            'val' => $stats['dibuka'] ?? 0,
            'cls' => 'is-open',
            'icon' => 'M22 11.08V12a10 10 0 11-5.93-9.14M22 4L12 14.01l-3-3',
        ],
        [
            'lbl' => 'Tutup ≤ 14 hari',
            'hint' => 'Segera ditutup',
            'val' => $stats['segera_tutup'] ?? 0,
            'cls' => 'is-soon',
            'icon' => 'M12 3a9 9 0 100 18 9 9 0 000-18zM12 7v5l3 2',
        ],
        [
            'lbl' => 'Belum dibuka',
            'hint' => 'Sudah dijadwalkan',
            'val' => $stats['belum_buka'] ?? 0,
            'cls' => 'is-wait',
            'icon' => 'M4 6h16v14H4zM4 10h16M8 3v4M16 3v4',
        ],
    ];
@endphp

<aside class="pmb-aside">
    <div class="card pmb-stat">
        <div class="pmb-stat__head">
            <h3>Statistik jalur</h3>
            <p>Ringkasan seluruh data PMB</p>
        </div>

        <div class="pmb-stat__total">
            <div>
                <span class="pmb-stat__val">{{ number_format($total, 0, ',', '.') }}</span>
                <span class="pmb-stat__lbl">Total jalur</span>
            </div>
            @if ($total > 0)
                <span class="pmb-stat__pill">{{ $pAktif }}% aktif</span>
            @endif
        </div>

        <div class="pmb-stat__bar" role="img"
            aria-label="{{ $stats['aktif'] ?? 0 }} jalur aktif dan {{ $stats['nonaktif'] ?? 0 }} nonaktif dari {{ $total }} jalur">
            <span class="pmb-stat__bar-ok" style="width:{{ $pAktif }}%"></span>
            <span class="pmb-stat__bar-off" style="width:{{ $pNon }}%"></span>
        </div>

        <div class="pmb-stat__legend">
            <span><i class="d-ok"></i>Aktif <b>{{ $stats['aktif'] ?? 0 }}</b></span>
            <span><i class="d-off"></i>Nonaktif <b>{{ $stats['nonaktif'] ?? 0 }}</b></span>
        </div>

        <div class="pmb-stat__sep"></div>

        <p class="pmb-stat__title">Status pendaftaran</p>
        <ul class="pmb-stat__list">
            @foreach ($statusRows as $r)
                <li class="{{ $r['cls'] }}">
                    <span class="pmb-stat__ico" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="{{ $r['icon'] }}" />
                        </svg>
                    </span>
                    <span class="pmb-stat__meta">
                        <span class="pmb-stat__name">{{ $r['lbl'] }}</span>
                        <span class="pmb-stat__hint">{{ $r['hint'] }}</span>
                    </span>
                    <b class="pmb-stat__num">{{ $r['val'] }}</b>
                    <span class="pmb-stat__mini" title="{{ $pct($r['val']) }}% dari total jalur">
                        <i style="width:{{ $pct($r['val']) }}%"></i>
                    </span>
                </li>
            @endforeach
        </ul>

        @if (($stats['segera_tutup'] ?? 0) > 0)
            <div class="pmb-stat__alert" role="status">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3l10 18H2zM12 10v5M12 18h.01" />
                </svg>
                <span><strong>{{ $stats['segera_tutup'] }} jalur</strong> akan ditutup dalam 14 hari.
                    Periksa kembali jadwal atau umumkan ke calon mahasiswa.</span>
            </div>
        @endif

        <p class="pmb-stat__note">
            Dihitung dari seluruh data, bukan hanya halaman ini. Diperbarui setiap muat ulang.
        </p>
    </div>

    <div class="card">
        <h3 style="margin-bottom:10px;">Saran pengisian</h3>
        <ul class="pmb-tips">
            <li>Satu jalur = satu periode/skema (Reguler, Prestasi, dll.).</li>
            <li>Isi tanggal buka–tutup agar status “Sedang dibuka” akurat.</li>
            <li>Syarat &amp; langkah: satu baris satu poin.</li>
        </ul>
    </div>
</aside>
