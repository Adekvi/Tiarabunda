@php
    $persenAktif = $counts['all'] > 0 ? round(($counts['aktif'] / $counts['all']) * 100) : 0;
@endphp

<div class="tst-kpi-grid" aria-label="Ringkasan statistik testimoni">
    <div class="card tst-kpi-card tst-kpi-card--navy">
        <div class="tst-kpi-top">
            <span class="tst-kpi-lbl">Total Testimoni</span>
            <span class="tst-kpi-ico" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
            </span>
        </div>
        <div class="tst-kpi-val">{{ $counts['all'] }}</div>
        <div class="tst-kpi-foot">Seluruh cerita tersimpan di master</div>
    </div>

    <div class="card tst-kpi-card tst-kpi-card--emerald">
        <div class="tst-kpi-top">
            <span class="tst-kpi-lbl">Tayang di Beranda</span>
            <span class="tst-kpi-pill tst-kpi-pill--ok">{{ $persenAktif }}% Aktif</span>
        </div>
        <div class="tst-kpi-val">{{ $counts['aktif'] }}</div>
        <div class="tst-kpi-foot">{{ $counts['nonaktif'] }} cerita sedang disembunyikan</div>
    </div>

    <div class="card tst-kpi-card tst-kpi-card--gold">
        <div class="tst-kpi-top">
            <span class="tst-kpi-lbl">Indeks Kepuasan</span>
            <span class="tst-kpi-pill tst-kpi-pill--gold">★★★★★</span>
        </div>
        <div class="tst-kpi-val">{{ $stats['avg_rating'] }}<small>/5.0</small></div>
        <div class="tst-kpi-foot">Rata-rata rating testimoni aktif</div>
    </div>

    <div class="card tst-kpi-card">
        <div class="tst-kpi-top">
            <span class="tst-kpi-lbl">Dominasi Kategori</span>
            <span class="tst-kpi-ico" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                    <path d="M6 12v5c3 3 9 3 12 0v-5" />
                </svg>
            </span>
        </div>
        <div class="tst-kpi-val tst-kpi-val--sm">
            {{ $stats['kategori']['alumni'] }} Alumni
        </div>
        <div class="tst-kpi-foot">
            {{ $stats['kategori']['mahasiswa'] }} Mahasiswa &bull; {{ $stats['kategori']['beasiswa'] }} Beasiswa
        </div>
    </div>
</div>
