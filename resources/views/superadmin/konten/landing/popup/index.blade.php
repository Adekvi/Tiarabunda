<x-dashboard.layout.app title="Welcome Pop-up" pageTitle="Master Welcome Pop-up">
    @php
        $totalSemua = (int) ($aktifCount ?? 0) + (int) ($arsipCount ?? 0);
        $pAktif = $totalSemua > 0 ? (int) round((($aktifCount ?? 0) / $totalSemua) * 100) : 0;
        $pArsip = $totalSemua > 0 ? 100 - $pAktif : 0;
    @endphp

    <div class="pop-page">
        @if (session('success'))
            <div class="al pop-al" role="status">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="pop-layout">
            {{-- Kolom Kiri: Header, Tab, Filter & Tabel --}}
            <div class="pop-main">
                <div class="card pop-hero">
                    <div>
                        <p class="pop-crumb">
                            <span>Beranda</span>
                            <span aria-hidden="true">/</span>
                            <span class="is-current">Welcome Pop-up</span>
                        </p>
                        <h2 class="pop-hero__title">Kelola Welcome Pop-up</h2>
                        <p class="pop-hero__desc">
                            Atur slide pop-up multi-layer pada landing utama (PMB, Jurusan, Beasiswa, Pusat Informasi).
                        </p>
                    </div>
                    <a href="{{ route('superadmin.popup.create') }}" class="btn pop-hero__cta">
                        + Tambah Slide Pop-up
                    </a>
                </div>

                <div class="pop-tabs" role="tablist">
                    <a class="pop-tab {{ $viewMode !== 'trashed' ? 'is-active' : '' }}"
                        href="{{ route('superadmin.popup.index', array_filter(['kategori' => $kategori, 'q' => $search])) }}">
                        Slide Aktif / Terdaftar
                        <span class="pop-tab__badge">{{ $aktifCount }}</span>
                    </a>
                    <a class="pop-tab {{ $viewMode === 'trashed' ? 'is-active' : '' }}"
                        href="{{ route('superadmin.popup.index', array_filter(['view' => 'trashed', 'kategori' => $kategori, 'q' => $search])) }}">
                        Arsip Terhapus
                        <span class="pop-tab__badge is-warn">{{ $arsipCount }}</span>
                    </a>
                </div>

                <form method="GET" action="{{ route('superadmin.popup.index') }}" class="card pop-filter">
                    @if ($viewMode === 'trashed')
                        <input type="hidden" name="view" value="trashed">
                    @endif

                    <div class="pop-filter__field">
                        <label for="pop-kat">Kategori</label>
                        <select id="pop-kat" name="kategori" class="pop-input">
                            <option value="">-- Semua Kategori --</option>
                            @foreach ($kategoris as $key => $label)
                                <option value="{{ $key }}" @selected($kategori === $key)>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pop-filter__field pop-filter__field--grow">
                        <label for="pop-q">Pencarian</label>
                        <input id="pop-q" type="text" name="q" value="{{ $search }}"
                            class="pop-input" placeholder="Cari judul, badge, atau deskripsi...">
                    </div>

                    <div class="pop-filter__actions">
                        <button type="submit" class="btn">Filter</button>
                        <a href="{{ route('superadmin.popup.index', $viewMode === 'trashed' ? ['view' => 'trashed'] : []) }}"
                            class="btn pop-btn-ghost">Reset</a>
                    </div>
                </form>

                <div class="card pop-card">
                    <div class="pop-card__head">
                        <h3>{{ $viewMode === 'trashed' ? 'Arsip Slide Pop-up' : 'Daftar Slide Pop-up' }}</h3>
                        <span class="pop-count">{{ $popups->total() }} data</span>
                    </div>

                    <div class="pop-table-wrap">
                        <table class="pop-table">
                            <thead>
                                <tr>
                                    <th class="is-num">Urutan</th>
                                    <th>Kategori</th>
                                    <th>Judul &amp; Badge</th>
                                    <th>CTA Utama</th>
                                    <th class="is-status">Status</th>
                                    <th class="is-actions">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($popups as $row)
                                    <tr>
                                        <td class="is-num"><strong>{{ $row->urutan }}</strong></td>
                                        <td>
                                            <span class="pop-tag">
                                                {{ $kategoris[$row->kategori] ?? $row->kategori }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="pop-title-cell">{{ $row->judul }}</div>
                                            @if ($row->badge)
                                                <div class="pop-sub-cell">{{ $row->badge }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($row->teks_tombol)
                                                <div class="pop-cta-lbl">{{ $row->teks_tombol }}</div>
                                            @endif
                                            @if ($row->url_tombol)
                                                <div class="pop-cta-url" title="{{ $row->url_tombol }}">
                                                    {{ $row->url_tombol }}
                                                </div>
                                            @endif
                                            @if (!$row->teks_tombol && !$row->url_tombol)
                                                <span class="pop-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="is-status">
                                            @if ($viewMode === 'trashed')
                                                <span class="pop-badge is-off">Diarsipkan</span>
                                            @elseif ($row->is_active)
                                                <span class="pop-badge is-on">Aktif</span>
                                            @else
                                                <span class="pop-badge is-off">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="is-actions">
                                            <div class="pop-actions">
                                                @if ($viewMode === 'trashed')
                                                    <form action="{{ route('superadmin.popup.restore', $row->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="pop-act is-ok">Pulihkan</button>
                                                    </form>
                                                    <form
                                                        action="{{ route('superadmin.popup.force-destroy', $row->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Hapus permanen slide ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="pop-act is-danger">Hapus
                                                            Permanen</button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('superadmin.popup.toggle', $row->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="pop-act is-mute">
                                                            {{ $row->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('superadmin.popup.edit', $row->id) }}"
                                                        class="pop-act is-edit">Edit</a>
                                                    <form action="{{ route('superadmin.popup.destroy', $row->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Pindahkan slide ini ke arsip?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="pop-act is-danger">Arsipkan</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="pop-empty">Belum ada data slide pop-up.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($popups->hasPages())
                        <div class="pop-pager">
                            <p>Menampilkan {{ $popups->firstItem() }}–{{ $popups->lastItem() }} dari
                                {{ $popups->total() }}</p>
                            <div>{{ $popups->links() }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kolom Kanan: Sidebar Informasi Welcome Pop-up --}}
            <aside class="pop-aside">
                <div class="card pop-stat">
                    <div class="pop-stat__head">
                        <h3>Ringkasan Pop-up</h3>
                        <p>Status data slide di database</p>
                    </div>

                    <div class="pop-stat__total">
                        <div>
                            <span class="pop-stat__val">{{ number_format($totalSemua, 0, ',', '.') }}</span>
                            <span class="pop-stat__lbl">Total slide</span>
                        </div>
                        @if ($totalSemua > 0)
                            <span class="pop-stat__pill">{{ $pAktif }}% terdaftar</span>
                        @endif
                    </div>

                    <div class="pop-stat__bar" role="img"
                        aria-label="{{ $aktifCount }} terdaftar dan {{ $arsipCount }} diarsipkan">
                        <span class="pop-stat__bar-ok" style="width: {{ $pAktif }}%"></span>
                        <span class="pop-stat__bar-off" style="width: {{ $pArsip }}%"></span>
                    </div>

                    <div class="pop-stat__legend">
                        <span><i class="d-ok"></i>Terdaftar <b>{{ $aktifCount }}</b></span>
                        <span><i class="d-off"></i>Arsip <b>{{ $arsipCount }}</b></span>
                    </div>
                </div>

                <div class="card pop-info-box">
                    <h3>Tentang Welcome Pop-up</h3>
                    <p>
                        Pop-up tampil otomatis saat pengunjung membuka halaman utama landing (<strong>/</strong>)
                        dalam bentuk slider per topik.
                    </p>
                </div>

                <div class="card pop-info-box">
                    <h3>Panduan Pengelolaan</h3>
                    <ul class="pop-tips">
                        <li>Slide urutan terkecil muncul sebagai layer pertama (default: PMB).</li>
                        <li>Hanya slide berstatus <strong>Aktif</strong> yang tampil di landing.</li>
                        <li>Data yang dihapus masuk ke <strong>Arsip Terhapus</strong> dan bisa dipulihkan.</li>
                        <li>Cache landing otomatis diperbarui saat data disimpan atau diubah.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/beranda/popup/popup.css') }}">
    @endpush
</x-dashboard.layout.app>
