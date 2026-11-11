<x-dashboard.layout.app title="Info Terkini" page-title="Info Terkini">

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/info/info.css') }}">
    @endpush

    <div class="g">

        @if (session('success'))
            <div class="al al-success" role="status" style="display: flex; align-items: center; gap: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <p class="breadcrumb">
                    Superadmin <span style="opacity: .5;">/</span> Beranda <span style="opacity: .5;">/</span>
                    <a href="{{ route('superadmin.info.index') }}">Info Terkini</a>
                </p>
                <h2>Manajemen Info Terkini</h2>
                <p class="subtitle">Kelola pengumuman, beasiswa, dan informasi yang tampil di slider beranda.</p>
            </div>
            <a href="{{ route('superadmin.info.create') }}" class="btn" style="white-space: nowrap;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14"></path>
                    <path d="M5 12h14"></path>
                </svg>
                Tambah Info
            </a>
        </div>

        {{-- MAIN GRID --}}
        <div class="g g2" style="align-items: start;">

            {{-- KIRI: TABEL --}}
            <div class="card" style="min-width: 0; padding: 0; overflow: hidden;">
                <div class="ch2" style="padding: 16px 20px; margin: 0; border-bottom: 1px solid var(--line);">
                    <h2 style="margin: 0; font-size: 1rem;">Daftar Info</h2>
                    <span class="badge-cat">{{ $items->count() }} data</span>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 48px; text-align: center;">No</th>
                                <th style="width: 72px;">Poster</th>
                                <th>Kategori</th>
                                <th>Judul</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $i => $item)
                                <tr>
                                    <td style="text-align: center; color: var(--mut); font-weight: 600;">
                                        {{ $i + 1 }}</td>
                                    <td>
                                        <div class="poster-cell">
                                            @if ($item->img)
                                                <img src="{{ asset('storage/' . $item->img) }}"
                                                    alt="{{ $item->t }}">
                                            @else
                                                <span class="empty">No img</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td><span class="badge-cat">{{ $item->k ?: '—' }}</span></td>
                                    <td class="title-cell">
                                        <strong>{{ $item->t }}</strong>
                                        @if ($item->d)
                                            <small>{{ \Illuminate\Support\Str::limit($item->d, 60) }}</small>
                                        @endif
                                    </td>
                                    <td style="color: var(--mut); white-space: nowrap; font-size: 0.85rem;">
                                        {{ $item->tgl }}</td>
                                    <td>
                                        <form action="{{ route('superadmin.info.toggle', $item) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="status-btn {{ $item->urip ? 'ok' : 'err' }}">
                                                {{ $item->urip ? '● Aktif' : '○ Draf' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="action-group">
                                            <a href="{{ route('superadmin.info.edit', $item) }}"
                                                class="btn gh btn-sm">Edit</a>
                                            <form action="{{ route('superadmin.info.destroy', $item) }}" method="POST"
                                                onsubmit="return confirm('Hapus info ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn gh btn-sm"
                                                    style="color: var(--err);">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <div class="icon">📭</div>
                                            <p style="margin: 0 0 10px; font-weight: 600; color: var(--ink);">Belum ada
                                                data Info Terkini</p>
                                            <a href="{{ route('superadmin.info.create') }}"
                                                style="color: var(--pri); font-weight: 600; text-decoration: none;">
                                                + Tambah data pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- KANAN: SIDEBAR (dengan overflow vertikal terisolasi) --}}
            <aside class="sidebar-scroll">
                <div class="g" style="gap: 16px;">

                    {{-- RINGKASAN STATISTIK --}}
                    <div class="card sidebar-card">
                        <h3>Ringkasan Statistik</h3>
                        <div class="stat-stack">

                            <div class="stat-tile">
                                <div class="ico pri">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                                <div class="meta">
                                    <span class="lbl">Total Data</span>
                                    <span class="val pri">{{ $items->count() }}</span>
                                </div>
                            </div>

                            <div class="stat-tile">
                                <div class="ico ok">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                                <div class="meta">
                                    <span class="lbl">Aktif Tayang</span>
                                    <span class="val ok">{{ $items->where('urip', true)->count() }}</span>
                                </div>
                            </div>

                            <div class="stat-tile">
                                <div class="ico warn">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                    </svg>
                                </div>
                                <div class="meta">
                                    <span class="lbl">Draf</span>
                                    <span class="val warn">{{ $items->where('urip', false)->count() }}</span>
                                </div>
                            </div>

                            <div class="stat-tile">
                                <div class="ico neu">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z">
                                        </path>
                                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                    </svg>
                                </div>
                                <div class="meta">
                                    <span class="lbl">Kategori</span>
                                    <span class="val">{{ $items->pluck('k')->filter()->unique()->count() }}</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- DISTRIBUSI KATEGORI --}}
                    <div class="card sidebar-card">
                        <h3>Distribusi Kategori</h3>
                        @php
                            $categories = $items->groupBy('k')->map->count()->sortDesc()->take(5);
                            $maxCat = $categories->max() ?: 1;
                        @endphp
                        @if ($categories->isEmpty())
                            <p style="margin: 0; font-size: 0.85rem; color: var(--mut);">Belum ada data.</p>
                        @else
                            <div>
                                @foreach ($categories as $cat => $count)
                                    <div class="dist-item">
                                        <div class="dist-head">
                                            <span class="name">{{ $cat ?: '—' }}</span>
                                            <span class="count">{{ $count }}</span>
                                        </div>
                                        <div class="dist-bar">
                                            <span style="width: {{ ($count / $maxCat) * 100 }}%;"></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- PANDUAN --}}
                    <div class="card sidebar-card">
                        <h3>Panduan Singkat</h3>
                        <ul class="guide-list">
                            <li>Gunakan poster portrait (rasio ±5:7).</li>
                            <li>Kategori singkat: <strong>Beasiswa</strong>, <strong>Pengumuman</strong>, dll.</li>
                            <li>Hanya status <strong>Aktif</strong> yang tampil di landing.</li>
                            <li>Klik badge status untuk mengubah tampil/sembunyi.</li>
                        </ul>
                    </div>

                </div>
            </aside>
        </div>
    </div>
</x-dashboard.layout.app>
