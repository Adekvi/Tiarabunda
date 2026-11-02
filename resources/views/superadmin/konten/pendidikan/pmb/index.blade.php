<x-dashboard.layout.app title="PMB" page-title="PMB">
    <div class="g">
        @if (session('success'))
            <div class="al" role="status">{{ session('success') }}</div>
        @endif

        <div class="g g2" style="align-items:start;">
            <div class="g" style="min-width:0;">
                <div class="card">
                    <div class="ch2">
                        <div>
                            <h2 style="margin-bottom:4px;">Kelola Jalur PMB</h2>
                            <p style="margin:0;font-size:0.85rem;color:var(--mut);">
                                Kelola informasi jalur Penerimaan Mahasiswa Baru (PMB) yang tampil di publik.
                            </p>
                        </div>
                        <a href="{{ route('superadmin.pmb.create') }}" class="btn">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                                <path d="M12 5v14" />
                                <path d="M5 12h14" />
                            </svg>
                            Tambah
                        </a>
                    </div>

                    <form method="GET" action="{{ route('superadmin.pmb.index') }}" class="faq-filter">
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
                                    placeholder="Cari nama atau tanda jalur..." autocomplete="off">
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
                                <a href="{{ route('superadmin.pmb.index') }}" class="btn gh">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">Daftar Data</h2>
                        <span class="faq-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($items->isEmpty())
                        <div class="faq-empty">
                            <p class="faq-empty__title">Belum ada data</p>
                            <p class="faq-empty__desc">Tambahkan data jalur pendaftaran PMB terlebih dahulu.</p>
                            <a href="{{ route('superadmin.pmb.create') }}" class="btn">Tambah data</a>
                        </div>
                    @else
                        <div class="tw">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width:48px;">#</th>
                                        <th>Nama Jalur</th>
                                        <th>Tanda / Slug</th>
                                        <th style="width:120px;">Waktu Buka</th>
                                        <th style="width:120px;">Waktu Tutup</th>
                                        <th style="width:80px;">Urutan</th>
                                        <th style="width:100px;">Status</th>
                                        <th style="width:128px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td style="color:var(--mut);">{{ $items->firstItem() + $loop->index }}</td>
                                            <td style="font-weight:600;white-space:normal;">{{ $item->jeneng }}</td>
                                            <td>{{ $item->tandha ?: '—' }}</td>
                                            <td>{{ $item->wektu_bukak ? $item->wektu_bukak->format('d/m/Y') : '—' }}
                                            </td>
                                            <td>{{ $item->wektu_tutup ? $item->wektu_tutup->format('d/m/Y') : '—' }}
                                            </td>
                                            <td>{{ $item->urutan }}</td>
                                            <td><span
                                                    class="badge {{ $item->urip ? 'ok' : 'err' }}">{{ $item->urip ? 'Aktif' : 'Nonaktif' }}</span>
                                            </td>
                                            <td>
                                                <div class="row-actions" role="group">
                                                    <a href="{{ route('superadmin.pmb.edit', $item) }}" class="ia"
                                                        title="Edit" aria-label="Edit">
                                                        <svg width="16" height="16" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            aria-hidden="true">
                                                            <path d="M12 20h9" />
                                                            <path
                                                                d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                                        </svg>
                                                    </a>
                                                    <form method="POST"
                                                        action="{{ route('superadmin.pmb.toggle', $item) }}">
                                                        @csrf @method('PATCH')
                                                        <button type="submit"
                                                            class="ia {{ $item->urip ? 'ia-mute' : 'ia-ok' }}"
                                                            title="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                            @if ($item->urip)
                                                                <svg width="16" height="16" viewBox="0 0 24 24"
                                                                    fill="none" stroke="currentColor"
                                                                    stroke-width="2" stroke-linecap="round"
                                                                    stroke-linejoin="round" aria-hidden="true">
                                                                    <path
                                                                        d="M17.94 17.94A10.9 10.9 0 0 1 12 20c-5 0-9.27-3.11-11-8 1-2.9 2.9-5.2 5.4-6.5" />
                                                                    <path d="M1 1l22 22" />
                                                                    <path
                                                                        d="M9.9 4.24A10.9 10.9 0 0 1 12 4c5 0 9.27 3.11 11 8a11.7 11.7 0 0 1-2.2 3.2" />
                                                                    <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24" />
                                                                </svg>
                                                            @else
                                                                <svg width="16" height="16"
                                                                    viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    aria-hidden="true">
                                                                    <path
                                                                        d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                                                    <circle cx="12" cy="12" r="3" />
                                                                </svg>
                                                            @endif
                                                        </button>
                                                    </form>
                                                    <form method="POST"
                                                        action="{{ route('superadmin.pmb.destroy', $item) }}"
                                                        onsubmit="return confirm('Hapus jalur PMB ini?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="ia ia-danger" title="Hapus">
                                                            <svg width="16" height="16" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                aria-hidden="true">
                                                                <path d="M3 6h18" />
                                                                <path d="M8 6V4h8v2" />
                                                                <path d="M19 6l-1 14H6L5 6" />
                                                                <path d="M10 11v6" />
                                                                <path d="M14 11v6" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($items->hasPages())
                            <div class="faq-pager">
                                <p>Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari
                                    {{ $items->total() }}</p>
                                <div>{{ $items->links() }}</div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Sidebar kanan --}}
            {{-- Sidebar kanan --}}
            @php
                $total = (int) $stats['total'];
                $pct = fn($n) => $total > 0 ? (int) round(($n / $total) * 100) : 0;
                $pAktif = $pct($stats['aktif']);
                $pNon = $total > 0 ? 100 - $pAktif : 0;

                $statusRows = [
                    [
                        'lbl' => 'Sedang dibuka',
                        'hint' => 'Menerima pendaftar saat ini',
                        'val' => $stats['dibuka'],
                        'cls' => 'is-open',
                        'icon' => 'M22 11.08V12a10 10 0 11-5.93-9.14M22 4L12 14.01l-3-3',
                    ],
                    [
                        'lbl' => 'Tutup ≤ 14 hari',
                        'hint' => 'Segera ditutup',
                        'val' => $stats['segera_tutup'],
                        'cls' => 'is-soon',
                        'icon' => 'M12 3a9 9 0 100 18 9 9 0 000-18zM12 7v5l3 2',
                    ],
                    [
                        'lbl' => 'Belum dibuka',
                        'hint' => 'Sudah dijadwalkan',
                        'val' => $stats['belum_buka'],
                        'cls' => 'is-wait',
                        'icon' => 'M4 6h16v14H4zM4 10h16M8 3v4M16 3v4',
                    ],
                ];
            @endphp
            <div class="g" style="gap:16px;">
                <div class="card">
                    <h3 style="margin-bottom:10px;">Tentang PMB</h3>
                    <p style="margin:0;font-size:0.88rem;color:var(--mut);line-height:1.65;">
                        Kelola jalur penerimaan mahasiswa baru. Hanya jalur <strong>aktif</strong>
                        yang tampil di halaman <strong>Pendidikan → PMB</strong> di situs publik.
                    </p>
                </div>

                {{-- ===== Statistik jalur ===== --}}
                <div class="card st">
                    <div class="st-head">
                        <h3>Statistik jalur</h3>
                        <p>Ringkasan seluruh data PMB</p>
                    </div>

                    <div class="st-total">
                        <div>
                            <span class="st-total__val">{{ $stats['total'] }}</span>
                            <span class="st-total__lbl">Total jalur</span>
                        </div>
                        @if ($total > 0)
                            <span class="st-pill">{{ $pAktif }}% aktif</span>
                        @endif
                    </div>

                    <div class="st-bar" role="img"
                        aria-label="{{ $stats['aktif'] }} jalur aktif dan {{ $stats['nonaktif'] }} nonaktif dari {{ $total }} jalur">
                        <span class="st-bar__ok" style="width:{{ $pAktif }}%"></span>
                        <span class="st-bar__off" style="width:{{ $pNon }}%"></span>
                    </div>
                    <div class="st-legend">
                        <span><i class="d-ok"></i>Aktif <b>{{ $stats['aktif'] }}</b></span>
                        <span><i class="d-off"></i>Nonaktif <b>{{ $stats['nonaktif'] }}</b></span>
                    </div>

                    <div class="st-sep"></div>

                    <p class="st-title">Status pendaftaran</p>
                    <ul class="st-list">
                        @foreach ($statusRows as $r)
                            <li class="{{ $r['cls'] }}">
                                <span class="st-ico" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="{{ $r['icon'] }}" />
                                    </svg>
                                </span>
                                <span class="st-meta">
                                    <span class="st-name">{{ $r['lbl'] }}</span>
                                    <span class="st-hint">{{ $r['hint'] }}</span>
                                </span>
                                <b class="st-num">{{ $r['val'] }}</b>
                                <span class="st-mini" title="{{ $pct($r['val']) }}% dari total jalur">
                                    <i style="width:{{ $pct($r['val']) }}%"></i>
                                </span>
                            </li>
                        @endforeach
                    </ul>

                    @if ($stats['segera_tutup'] > 0)
                        <div class="st-alert" role="status">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 3l10 18H2zM12 10v5M12 18h.01" />
                            </svg>
                            <span><strong>{{ $stats['segera_tutup'] }} jalur</strong> akan ditutup dalam 14 hari.
                                Periksa
                                kembali jadwal atau umumkan ke calon mahasiswa.</span>
                        </div>
                    @endif

                    <p class="st-note">Dihitung dari seluruh data, bukan hanya halaman ini. Diperbarui setiap muat
                        ulang.</p>
                </div>

                <div class="card">
                    <h3 style="margin-bottom:10px;">Saran pengisian</h3>
                    <ul style="margin:0;padding-left:18px;color:var(--mut);font-size:0.88rem;line-height:1.7;">
                        <li>Satu jalur = satu periode/skema (Reguler, Prestasi, dll.).</li>
                        <li>Isi tanggal buka–tutup agar status “Sedang dibuka” akurat.</li>
                        <li>Syarat &amp; langkah: satu baris satu poin.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <style>
            .st-head h3 {
                margin: 0;
            }

            .st-head p {
                margin: 2px 0 0;
                font-size: .78rem;
                color: var(--mut);
            }

            /* total + persentase */
            .st-total {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                gap: 12px;
                margin: 18px 0 14px;
            }

            .st-total__val {
                display: block;
                font-size: 2.5rem;
                font-weight: 800;
                line-height: 1;
                letter-spacing: -.03em;
                color: var(--ink);
                font-variant-numeric: tabular-nums;
            }

            .st-total__lbl {
                display: block;
                margin-top: 4px;
                font-size: .78rem;
                font-weight: 600;
                color: var(--mut);
            }

            .st-pill {
                padding: 3px 11px;
                border-radius: 99px;
                background: var(--ok-t);
                color: var(--ok);
                font-size: .75rem;
                font-weight: 700;
                white-space: nowrap;
            }

            /* bar bersegmen */
            .st-bar {
                display: flex;
                gap: 2px;
                height: 10px;
                border-radius: 99px;
                overflow: hidden;
                background: var(--line);
            }

            .st-bar span {
                display: block;
                height: 100%;
                transition: width .6s var(--ease);
            }

            .st-bar__ok {
                background: var(--ok);
            }

            .st-bar__off {
                background: var(--err);
                opacity: .7;
            }

            .st-legend {
                display: flex;
                flex-wrap: wrap;
                gap: 6px 18px;
                margin-top: 10px;
                font-size: .8rem;
                color: var(--mut);
            }

            .st-legend i {
                display: inline-block;
                width: 8px;
                height: 8px;
                margin-right: 6px;
                border-radius: 50%;
            }

            .st-legend b {
                margin-left: 2px;
                color: var(--ink);
            }

            .d-ok {
                background: var(--ok);
            }

            .d-off {
                background: var(--err);
            }

            .st-sep {
                height: 1px;
                margin: 18px 0 14px;
                background: var(--line);
            }

            .st-title {
                margin: 0 0 12px;
                font-size: .68rem;
                font-weight: 700;
                letter-spacing: .09em;
                text-transform: uppercase;
                color: var(--mut);
            }

            /* daftar status */
            .st-list {
                display: grid;
                gap: 14px;
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .st-list li {
                display: grid;
                grid-template-columns: auto minmax(0, 1fr) auto;
                gap: 6px 12px;
                align-items: center;
            }

            .st-ico {
                grid-row: 1 / 3;
                display: grid;
                place-items: center;
                width: 36px;
                height: 36px;
                border-radius: 10px;
                background: var(--tint);
                color: var(--tone);
            }

            .st-meta {
                display: grid;
                min-width: 0;
                line-height: 1.3;
            }

            .st-name {
                font-size: .88rem;
                font-weight: 700;
                color: var(--ink);
            }

            .st-hint {
                font-size: .74rem;
                color: var(--mut);
            }

            .st-num {
                font-size: 1.25rem;
                font-weight: 800;
                line-height: 1;
                color: var(--tone);
                font-variant-numeric: tabular-nums;
            }

            .st-mini {
                grid-column: 2 / 4;
                height: 4px;
                border-radius: 99px;
                background: var(--line);
                overflow: hidden;
            }

            .st-mini i {
                display: block;
                height: 100%;
                border-radius: 99px;
                background: var(--tone);
                transition: width .6s var(--ease);
            }

            .is-open {
                --tone: var(--pri);
                --tint: var(--pri-t);
            }

            .is-soon {
                --tone: #c05f0a;
                --tint: var(--acc-t);
            }

            .is-wait {
                --tone: var(--mut);
                --tint: var(--hov);
            }

            [data-theme='dark'] .is-soon {
                --tone: var(--acc);
            }

            /* peringatan */
            .st-alert {
                display: flex;
                gap: 10px;
                align-items: flex-start;
                margin-top: 18px;
                padding: 11px 13px;
                border-radius: 10px;
                border-left: 4px solid var(--acc);
                background: var(--acc-t);
                font-size: .8rem;
                line-height: 1.5;
                color: var(--ink);
            }

            .st-alert svg {
                flex: none;
                margin-top: 2px;
                color: var(--acc);
            }

            .st-note {
                margin: 16px 0 0;
                font-size: .72rem;
                color: var(--mut);
            }
        </style>
    @endpush

</x-dashboard.layout.app>
