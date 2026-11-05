<x-dashboard.layout.app title="Kegiatan" page-title="Kegiatan Kampus">
    <div class="g">
        @if (session('success'))
            <div class="al" role="status">{{ session('success') }}</div>
        @endif

        <div class="g kegiatan-layout">

            {{-- ===== KONTEN UTAMA (KIRI) ===== --}}
            <div class="g kegiatan-main">

                <div class="card">
                    <div class="ch2">
                        <div>
                            <h2 style="margin-bottom:4px;">Kelola Kegiatan Kampus</h2>
                            <p style="margin:0;font-size:0.85rem;color:var(--mut);">
                                Agenda dan acara kampus yang tampil di menu Informasi → Kegiatan.
                            </p>
                        </div>
                        <a href="{{ route('superadmin.kegiatan.create') }}" class="btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                                <path d="M12 5v14" />
                                <path d="M5 12h14" />
                            </svg>
                            Tambah
                        </a>
                    </div>

                    <form method="GET" action="{{ route('superadmin.kegiatan.index') }}" class="faq-filter">
                        <div class="f" style="margin-bottom:0;">
                            <label for="q">Cari</label>
                            <div class="field-icon">
                                <svg class="field-icon__svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    aria-hidden="true">
                                    <circle cx="11" cy="11" r="7" />
                                    <path d="M20 20l-3.5-3.5" />
                                </svg>
                                <input id="q" type="search" name="q" value="{{ request('q') }}"
                                    placeholder="Cari judul, lokasi, atau ringkasan…" autocomplete="off">
                            </div>
                        </div>
                        <div class="f" style="margin-bottom:0;min-width:150px;">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="">Semua</option>
                                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                            </select>
                        </div>
                        <div class="faq-filter__actions">
                            <button type="submit" class="btn">Terapkan</button>
                            @if (request()->hasAny(['q', 'status']))
                                <a href="{{ route('superadmin.kegiatan.index') }}" class="btn gh">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="card nw-list">
                    {{-- Header daftar --}}
                    <div class="nw-list__header">

                        <div class="nw-list__heading">
                            <div class="nw-list__title-row">
                                <h2 class="nw-list__title">
                                    {{ $trashed ? 'Sampah Kegiatan' : 'Daftar Kegiatan' }}
                                </h2>

                                @if ($trashed)
                                    <span class="nw-status nw-status--danger">
                                        <span class="nw-status__dot"></span>
                                        Data terhapus
                                    </span>
                                @elseif (request('status') === 'active')
                                    <span class="nw-status nw-status--success">
                                        <span class="nw-status__dot"></span>
                                        Data aktif
                                    </span>
                                @elseif (request('status') === 'inactive')
                                    <span class="nw-status nw-status--muted">
                                        <span class="nw-status__dot"></span>
                                        Data nonaktif
                                    </span>
                                @endif
                            </div>

                            <p class="nw-list__description">
                                @if ($items->total() > 0)
                                    Menampilkan daftar kegiatan kampus
                                    {{ $filtered ? 'berdasarkan filter yang dipilih.' : 'yang tersimpan dalam sistem.' }}
                                @else
                                    Belum ada data kegiatan yang dapat ditampilkan.
                                @endif
                            </p>
                        </div>

                        <div class="nw-list__toolbar">

                            {{-- Total data --}}
                            <div class="nw-total">
                                <div class="nw-total__icon">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <rect x="3" y="4" width="18" height="16" rx="2" />
                                        <path d="M8 9h8M8 13h8M8 17h4" />
                                    </svg>
                                </div>

                                <div class="nw-total__content">
                                    <span class="nw-total__label">Total data</span>
                                    <strong class="nw-total__value">
                                        {{ number_format($items->total(), 0, ',', '.') }}
                                    </strong>
                                </div>
                            </div>

                            {{-- Separator --}}
                            <span class="nw-toolbar__divider" aria-hidden="true"></span>

                            {{-- Per page --}}
                            <form method="GET" action="{{ route('superadmin.kegiatan.index') }}" class="nw-page-size">
                                @if (request()->filled('q'))
                                    <input type="hidden" name="q" value="{{ request('q') }}">
                                @endif

                                @if (request()->filled('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif

                                <label for="per_page" class="nw-page-size__label">
                                    Baris
                                </label>

                                <select id="per_page" name="per_page" class="nw-page-size__select"
                                    onchange="this.form.submit()" aria-label="Jumlah data per halaman">
                                    @foreach ([10, 25, 50, 100] as $n)
                                        <option value="{{ $n }}" @selected((int) $perPage === $n)>
                                            {{ $n }}
                                        </option>
                                    @endforeach
                                </select>

                                <span class="nw-page-size__suffix">
                                    / halaman
                                </span>
                            </form>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="nw-list__body">
                        @include('superadmin.konten.informasi.kegiatan.partials.table', [
                            'items' => $items,
                            'filtered' => $filtered,
                            'trashed' => $trashed,
                        ])
                    </div>

                    {{-- Pagination --}}
                    @include('superadmin.konten.informasi.kegiatan.partials.paginate', [
                        'items' => $items,
                    ])

                </div>

            </div>{{-- /.kegiatan-main --}}

            {{-- ===== SIDEBAR (KANAN) ===== --}}
            <aside class="g kegiatan-aside">

                <section class="card side-panel">
                    <div class="side-panel__heading">
                        <div class="side-icon side-icon--primary">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="17" rx="2" />
                                <path d="M16 2v4M8 2v4M3 10h18" />
                                <path d="m9 16 2 2 4-4" />
                            </svg>
                        </div>
                        <div>
                            <h3>Informasi Kegiatan</h3>
                            <p>Ringkasan pengelolaan</p>
                        </div>
                    </div>

                    <div class="side-info">
                        <div class="side-info__item">
                            <span>Modul</span>
                            <strong>Informasi Kampus</strong>
                        </div>
                        <div class="side-info__item">
                            <span>Halaman publik</span>
                            <strong>Informasi → Kegiatan</strong>
                        </div>
                        <div class="side-info__item">
                            <span>Pengelolaan</span>
                            <strong>Konten dan publikasi</strong>
                        </div>
                    </div>

                    <div class="side-note">
                        Kegiatan yang berstatus aktif dapat ditampilkan pada halaman
                        informasi kampus.
                    </div>
                </section>

                <section class="card side-panel">
                    <div class="side-panel__heading">
                        <div class="side-icon side-icon--success">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M3 3v18h18" />
                                <path d="m7 14 4-4 4 4 6-7" />
                            </svg>
                        </div>
                        <div>
                            <h3>Statistik Kegiatan</h3>
                            <p>Ringkasan data tersimpan</p>
                        </div>
                    </div>

                    <div class="activity-stats">
                        <div class="activity-stat">
                            <span class="activity-stat__label">Total kegiatan</span>
                            <strong>{{ $stats['total'] }}</strong>
                            <span class="activity-stat__caption">Seluruh data</span>
                        </div>

                        <div class="activity-stat">
                            <span class="activity-stat__label">Aktif</span>
                            <strong class="text-success">{{ $stats['aktif'] }}</strong>
                            <span class="activity-stat__caption">Dipublikasikan</span>
                        </div>

                        <div class="activity-stat">
                            <span class="activity-stat__label">Mendatang</span>
                            <strong class="text-primary">{{ $stats['mendatang'] }}</strong>
                            <span class="activity-stat__caption">Belum dimulai</span>
                        </div>

                        <div class="activity-stat">
                            <span class="activity-stat__label">Berlangsung</span>
                            <strong class="text-warning">{{ $stats['berlangsung'] }}</strong>
                            <span class="activity-stat__caption">Sedang berjalan</span>
                        </div>
                    </div>
                </section>

                <section class="card side-panel">
                    <div class="side-panel__heading">
                        <div class="side-icon side-icon--warning">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4M12 8h.01" />
                            </svg>
                        </div>
                        <div>
                            <h3>Tips Pengelolaan</h3>
                            <p>Rekomendasi pengisian konten</p>
                        </div>
                    </div>

                    <div class="side-tips">
                        <div class="side-tip">
                            <span class="side-tip__number">01</span>
                            <div>
                                <strong>Gunakan judul yang jelas</strong>
                                <p>Hindari judul terlalu panjang dan gunakan nama acara yang mudah dikenali.</p>
                            </div>
                        </div>

                        <div class="side-tip">
                            <span class="side-tip__number">02</span>
                            <div>
                                <strong>Lengkapi jadwal kegiatan</strong>
                                <p>Isi waktu mulai dan selesai agar pengunjung memperoleh informasi yang tepat.</p>
                            </div>
                        </div>

                        <div class="side-tip">
                            <span class="side-tip__number">03</span>
                            <div>
                                <strong>Gunakan poster berkualitas</strong>
                                <p>Pilih gambar yang relevan dan mudah dibaca pada perangkat mobile.</p>
                            </div>
                        </div>

                        <div class="side-tip">
                            <span class="side-tip__number">04</span>
                            <div>
                                <strong>Kelola status publikasi</strong>
                                <p>Nonaktifkan kegiatan yang sudah tidak relevan tanpa harus menghapus datanya.</p>
                            </div>
                        </div>
                    </div>
                </section>

            </aside>

        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/informasi/kegiatan/index.css') }}">
    @endpush
</x-dashboard.layout.app>
