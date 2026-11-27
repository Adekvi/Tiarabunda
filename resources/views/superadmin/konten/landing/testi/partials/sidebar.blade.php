@php
    $totalMaster = max(1, (int) $counts['all']);
    $katBars = [
        [
            'key' => 'alumni',
            'label' => 'Alumni Berkarier',
            'count' => (int) ($stats['kategori']['alumni'] ?? 0),
            'color' => '#173b66',
        ],
        [
            'key' => 'mahasiswa',
            'label' => 'Mahasiswa Aktif',
            'count' => (int) ($stats['kategori']['mahasiswa'] ?? 0),
            'color' => '#059669',
        ],
        [
            'key' => 'beasiswa',
            'label' => 'Penerima Beasiswa',
            'count' => (int) ($stats['kategori']['beasiswa'] ?? 0),
            'color' => '#d97706',
        ],
    ];
@endphp

<aside class="tst-sidebar" aria-label="Navigasi status dan analitik testimoni">
    {{-- Kartu 1: Status Sinkronisasi Beranda --}}
    <div class="card tst-sync-card">
        <div class="tst-sync-head">
            <span class="tst-sync-pulse {{ $counts['aktif'] > 0 ? 'is-live' : 'is-off' }}"></span>
            <strong>Status Tayang Beranda</strong>
        </div>
        @if ($counts['all'] === 0)
            <p>Belum ada data master. Beranda saat ini menampilkan <strong>4 cerita bawaan</strong>.</p>
        @elseif ($counts['aktif'] > 0)
            <p><strong>{{ $counts['aktif'] }} cerita aktif</strong> sedang ditampilkan secara langsung di bagian
                <code>#testimoni</code>.</p>
        @else
            <p>Semua testimoni dinonaktifkan. Bagian <code>#testimoni</code> di beranda otomatis disembunyikan.</p>
        @endif
        <a href="{{ url('/#testimoni') }}" target="_blank" rel="noopener noreferrer" class="tst-sync-link">
            Lihat Bagian Testimoni di Beranda ↗
        </a>
    </div>

    {{-- Kartu 2: Navigasi Status Data --}}
    <div class="card tst-side-card">
        <div class="tst-side-title">Filter Status Data</div>

        <nav class="tst-side-nav">
            <a href="{{ route('superadmin.testimoni.index', array_filter(['q' => $q, 'kategori' => $kategori])) }}"
                class="tst-side-link {{ $status === '' ? 'is-active' : '' }}">
                <span class="tst-side-lbl">
                    <span class="tst-side-ico">◈</span>
                    <span>Semua Data</span>
                </span>
                <span class="tst-side-count">{{ $counts['all'] }}</span>
            </a>

            <a href="{{ route('superadmin.testimoni.index', array_filter(['status' => 'aktif', 'q' => $q, 'kategori' => $kategori])) }}"
                class="tst-side-link {{ $status === 'aktif' ? 'is-active' : '' }}">
                <span class="tst-side-lbl">
                    <span class="tst-side-ico tst-ico--ok">●</span>
                    <span>Aktif Tayang</span>
                </span>
                <span class="tst-side-count tst-side-count--ok">{{ $counts['aktif'] }}</span>
            </a>

            <a href="{{ route('superadmin.testimoni.index', array_filter(['status' => 'nonaktif', 'q' => $q, 'kategori' => $kategori])) }}"
                class="tst-side-link {{ $status === 'nonaktif' ? 'is-active' : '' }}">
                <span class="tst-side-lbl">
                    <span class="tst-side-ico">○</span>
                    <span>Nonaktif</span>
                </span>
                <span class="tst-side-count">{{ $counts['nonaktif'] }}</span>
            </a>

            <div class="tst-side-sep"></div>

            <a href="{{ route('superadmin.testimoni.index', array_filter(['status' => 'trash', 'q' => $q, 'kategori' => $kategori])) }}"
                class="tst-side-link tst-side-link--trash {{ $isTrash ? 'is-active' : '' }}">
                <span class="tst-side-lbl">
                    <span class="tst-side-ico">⌫</span>
                    <span>Tong Sampah</span>
                </span>
                <span class="tst-side-count tst-side-count--err">{{ $counts['trash'] }}</span>
            </a>
        </nav>
    </div>

    {{-- Kartu 3: Komposisi Kategori --}}
    <div class="card tst-side-card">
        <div class="tst-side-title">Komposisi Kategori</div>

        <div class="tst-cat-bars">
            @foreach ($katBars as $bar)
                @php
                    $pct = $counts['all'] > 0 ? round(($bar['count'] / $totalMaster) * 100) : 0;
                @endphp
                <a href="{{ route('superadmin.testimoni.index', array_filter(['kategori' => $bar['key'], 'status' => $isTrash ? 'trash' : $status, 'q' => $q])) }}"
                    class="tst-bar-item {{ $kategori === $bar['key'] ? 'is-selected' : '' }}">
                    <div class="tst-bar-meta">
                        <span>{{ $bar['label'] }}</span>
                        <strong>{{ $bar['count'] }} <small>({{ $pct }}%)</small></strong>
                    </div>
                    <div class="tst-bar-track">
                        <span class="tst-bar-fill"
                            style="width: {{ $pct }}%; background: {{ $bar['color'] }};"></span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Kartu 4: Tips Kurasi --}}
    <div class="card tst-tip-card">
        <div class="tst-tip-badge">Panduan Kurasi</div>
        <p>Item dengan <strong>urutan terkecil (#1)</strong> otomatis menjadi <em>Spotlight Utama</em> di bagian paling
            atas seksi testimoni.</p>
    </div>
</aside>
