@php
    $total = (int) ($stats['total'] ?? 0);
    $aktif = (int) ($stats['aktif'] ?? 0);
    $terbit = (int) ($stats['terbit'] ?? 0);
    $draf = (int) ($stats['draf'] ?? 0);
    $nonaktif = max($total - $aktif, 0);
    $pct = fn($n) => $total > 0 ? (int) round(($n / $total) * 100) : 0;
    $filtered = request()->hasAny(['q', 'status']);
@endphp

<x-dashboard.layout.app title="Berita" page-title="Berita">
    <div class="g">
        @if (session('success'))
            <div class="al nw-flash" role="status">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <path d="M22 4L12 14.01l-3-3" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="nw-page">
            <div class="nw-main">
                {{-- Header + filter --}}
                <div class="div nw-main">
                    <div class="card nw-head">
                        <div class="ch2">
                            <div>
                                <p class="nw-crumb">Informasi <span>/</span> Berita</p>
                                <h2 style="margin:0 0 4px;">Kelola Berita</h2>
                                <p class="nw-sub">Berita kampus yang tampil di menu Informasi → Berita pada situs
                                    publik.
                                </p>
                            </div>
                            <a href="{{ route('superadmin.berita.create') }}" class="btn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                                    <path d="M12 5v14" />
                                    <path d="M5 12h14" />
                                </svg>
                                Tambah berita
                            </a>
                        </div>

                        <form method="GET" action="{{ route('superadmin.berita.index') }}" class="faq-filter">
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
                                        placeholder="Cari judul atau ringkasan…" autocomplete="off">
                                </div>
                            </div>
                            <div class="f" style="margin-bottom:0;min-width:150px;">
                                <label for="status">Status</label>
                                <select id="status" name="status">
                                    <option value="">Semua</option>
                                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                                    <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                                    <option value="trashed" @selected(request('status') === 'trashed')>
                                        Terhapus
                                        @if (($stats['trashed'] ?? 0) > 0)
                                            ({{ $stats['trashed'] }})
                                        @endif
                                    </option>
                                </select>
                            </div>
                            <div class="faq-filter__actions">
                                <button type="submit" class="btn">Terapkan</button>
                                @if ($filtered)
                                    <a href="{{ route('superadmin.berita.index') }}" class="btn gh">Reset</a>
                                @endif
                            </div>
                        </form>


                        {{-- @if ($filtered)
                            <div class="nw-chips" aria-label="Filter aktif">
                                <span class="nw-chips__lbl">Filter aktif:</span>
                                @if (request('q'))
                                    <span class="nw-chip">Kata kunci: “{{ request('q') }}”</span>
                                @endif
                                @if (request('status'))
                                    <select id="status" name="status">
                                        <option value="">Semua</option>
                                        <option value="active" @selected(request('status') === 'active')>Aktif</option>
                                        <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                                        <option value="trashed" @selected(request('status') === 'trashed')>
                                            Terhapus @if (($stats['trashed'] ?? 0) > 0)
                                                ({{ $stats['trashed'] }})
                                            @endif
                                        </option>
                                    </select>
                                @endif
                            </div>
                        @endif --}}
                    </div>
                </div>

                {{-- Daftar --}}
                <div class="card nw-list">
                    <div class="ch2"
                        style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 16px;">

                        <!-- Bagian Kiri: Judul dan Deskripsi -->
                        <div>
                            <h2 style="margin: 0; font-size: 1.25rem; font-weight: 600;">Daftar berita</h2>
                            <p class="nw-sub" style="margin: 4px 0 0 0; color: #64748b; font-size: 0.875rem;">
                                @if ($items->total())
                                    Geser tabel ke kanan untuk melihat semua kolom.
                                @else
                                    Tidak ada data.
                                @endif
                            </p>
                        </div>

                        <!-- Bagian Kanan: Total Data & Filter Tampilan -->
                        <div style="display: flex; align-items: center; gap: 12px;">

                            <!-- Badge Total Data -->
                            <span class="faq-count"
                                style="font-size: 0.875rem; font-weight: 500; padding: 4px 12px; border-radius: 9999px;">
                                {{ $items->total() }} data
                            </span>

                            <!-- Dropdown Per Page -->
                            <div class="f" style="margin-bottom: 0; min-width: 80px;">
                                <select id="per_page" name="per_page"
                                    style="width: 100%; padding: 6px 12px; border-radius: 6px; outline: none; cursor: pointer;">
                                    @foreach ([10, 25, 50, 100] as $n)
                                        <option value="{{ $n }}" @selected((int) request('per_page', 10) === $n)>
                                            {{ $n }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    @include('superadmin.konten.informasi.berita.partials.table', [
                        'items' => $items,
                        'filtered' => $filtered,
                        'trashed' => $trashed ?? request('status') === 'trashed',
                    ])

                    @include('superadmin.konten.informasi.berita.partials.paginate', [
                        'items' => $items,
                    ])

                </div>
            </div>

            <aside class="nw-aside" aria-label="Ringkasan berita">

                {{-- Ringkasan --}}
                <div class="card nw-sum">
                    <div class="nw-sum__top">
                        <div>
                            <h3>Ringkasan berita</h3>
                            <p>Dihitung dari seluruh data</p>
                        </div>
                        @if ($total > 0)
                            <span class="nw-pill">{{ $pct($terbit) }}% terbit</span>
                        @endif
                    </div>

                    <div class="nw-big">
                        <span class="nw-big__val">{{ $total }}</span>
                        <span class="nw-big__lbl">Total berita</span>
                    </div>

                    <div class="nw-seg" role="img"
                        aria-label="{{ $terbit }} terbit, {{ $draf }} belum terbit atau terjadwal, dari {{ $total }} berita">
                        <span class="nw-seg__a" style="width:{{ $pct($terbit) }}%"></span>
                        <span class="nw-seg__b" style="width:{{ $pct($draf) }}%"></span>
                    </div>

                    <ul class="nw-rows">
                        <li class="is-ok">
                            <span class="nw-ico" aria-hidden="true">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14M22 4L12 14.01l-3-3" />
                                </svg>
                            </span>
                            <span class="nw-meta"><b>Sudah terbit</b><small>Tampil di situs publik</small></span>
                            <span class="nw-num">{{ $terbit }}</span>
                        </li>
                        <li class="is-soon">
                            <span class="nw-ico" aria-hidden="true">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 3a9 9 0 100 18 9 9 0 000-18zM12 7v5l3 2" />
                                </svg>
                            </span>
                            <span class="nw-meta"><b>Belum / terjadwal</b><small>Menunggu waktu terbit</small></span>
                            <span class="nw-num">{{ $draf }}</span>
                        </li>
                        <li class="is-pri">
                            <span class="nw-ico" aria-hidden="true">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </span>
                            <span class="nw-meta"><b>Status aktif</b><small>Diaktifkan oleh admin</small></span>
                            <span class="nw-num">{{ $aktif }}</span>
                        </li>
                        <li class="is-off">
                            <span class="nw-ico" aria-hidden="true">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 3a9 9 0 100 18 9 9 0 000-18zM5.6 5.6l12.8 12.8" />
                                </svg>
                            </span>
                            <span class="nw-meta"><b>Nonaktif</b><small>Disembunyikan dari publik</small></span>
                            <span class="nw-num">{{ $nonaktif }}</span>
                        </li>
                    </ul>

                    @if ($draf > 0)
                        <div class="nw-note" role="status">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4M12 8h.01" />
                            </svg>
                            <span><strong>{{ $draf }} berita</strong> belum tampil karena tanggal terbitnya
                                belum tiba atau belum diisi.</span>
                        </div>
                    @endif
                </div>

                {{-- Aturan tampil --}}
                <div class="card nw-card">
                    <div class="nw-card__head">
                        <span class="nw-card__ico" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4M12 8h.01" />
                            </svg>
                        </span>
                        <h3>Kapan berita tampil?</h3>
                    </div>
                    <ol class="nw-steps">
                        <li>Status berita <strong>Aktif</strong>.</li>
                        <li>Tanggal terbit sudah <strong>tiba</strong> (≤ hari ini).</li>
                        <li>Urutan kecil tampil lebih dulu.</li>
                    </ol>
                </div>

                {{-- Panduan --}}
                <div class="nw-card card">
                    <div class="nw-card__head">
                        <span class="nw-card__ico nw-card__ico--acc" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                            </svg>
                        </span>
                        <h3>Tips penulisan</h3>
                    </div>
                    <ul class="nw-tips">
                        <li>Judul singkat, informatif, maksimal ±10 kata.</li>
                        <li>Ringkasan dipakai pada kartu daftar berita.</li>
                        <li>Gunakan gambar sampul landscape agar rapi.</li>
                        <li>Atur tanggal terbit untuk publikasi terjadwal.</li>
                    </ul>
                    <a href="{{ route('superadmin.berita.create') }}" class="btn nw-cta">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                        Tulis berita baru
                    </a>
                </div>
            </aside>
        </div>
    </div>

    {{-- @include('superadmin.konten.layanan.fasilitas.modal.confir-modal') --}}

    @push('css')
        <link rel="stylesheet" href="{{ asset('admin/landing/informasi/berita/index.css') }}">
    @endpush
</x-dashboard.layout.app>

{{-- oke sementara itu dulu, kita lanjut ke menu lainnya mana yang lebih proper secara fungsi juga seperti yang telah saya jelaskan sebelumnya, bisa cek direpository terlebih dahulu --}}
