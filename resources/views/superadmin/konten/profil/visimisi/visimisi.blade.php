@php
    // $stats (opsional) dari controller: total, visi, misi, aktif, nonaktif, live_visi, live_misi.
    // Jika tidak dikirim, dihitung dari data halaman ini.
    $col = $items->getCollection();
    $total = $stats['total'] ?? $items->total();
    $jmlVisi = $stats['visi'] ?? $col->where('bagean', 'visi')->count();
    $jmlMisi = $stats['misi'] ?? $col->where('bagean', 'misi')->count();
    $aktif = $stats['aktif'] ?? $col->where('urip', true)->count();
    $nonaktif = $stats['nonaktif'] ?? max($total - $aktif, 0);
    $liveVisi = $stats['live_visi'] ?? $col->where('bagean', 'visi')->firstWhere('urip', true);
    $liveMisi = $stats['live_misi'] ?? $col->where('bagean', 'misi')->firstWhere('urip', true);
    $poinMisi = $liveMisi ? array_values(array_filter($liveMisi->dhaptar ?? [])) : [];
@endphp

<x-dashboard.layout.app title="Visi & Misi" page-title="Visi & Misi">
    <div class="g">
        @if (session('success'))
            <div class="al" role="status">{{ session('success') }}</div>
        @endif

        {{-- Header + filter --}}
        <div class="card">
            <div class="ch2">
                <div>
                    <h2 style="margin-bottom: 4px;">Kelola Visi & Misi</h2>
                    <p style="margin: 0; font-size: 0.85rem; color: var(--mut);">
                        <strong>Visi</strong> berupa narasi singkat, <strong>misi</strong> berupa daftar poin.
                        Tampil di halaman Profil &rsaquo; Visi &amp; Misi.
                    </p>
                </div>
                <a href="{{ route('superadmin.visimisi.create') }}" class="btn">+ Tambah visi/misi</a>
            </div>

            <form method="GET" action="{{ route('superadmin.visimisi.index') }}" class="faq-filter faq-filter--4">
                <div class="f">
                    <label for="q">Cari</label>
                    <div class="field-icon">
                        <svg class="field-icon__svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" />
                            <path d="M20 20l-3.5-3.5" />
                        </svg>
                        <input id="q" type="search" name="q" value="{{ request('q') }}"
                            placeholder="Cari judul atau isi…" autocomplete="off">
                    </div>
                </div>
                <div class="f">
                    <label for="bagean">Bagian</label>
                    <select id="bagean" name="bagean">
                        <option value="">Semua</option>
                        <option value="visi" @selected(request('bagean') === 'visi')>Visi</option>
                        <option value="misi" @selected(request('bagean') === 'misi')>Misi</option>
                    </select>
                </div>
                <div class="f">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua</option>
                        <option value="active" @selected(request('status') === 'active')>Aktif</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                    </select>
                </div>
                <div class="faq-filter__actions">
                    <button type="submit" class="btn">Terapkan</button>
                    @if (request()->hasAny(['q', 'status', 'bagean']))
                        <a href="{{ route('superadmin.visimisi.index') }}" class="btn gh">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Ringkasan --}}
        <div class="kpis">
            <div class="card kpi">
                <small>Total data</small>
                <b>{{ $total }}</b>
            </div>
            <div class="card kpi kpi--acc">
                <small>Visi</small>
                <b>{{ $jmlVisi }}</b>
            </div>
            <div class="card kpi kpi--acc">
                <small>Misi</small>
                <b>{{ $jmlMisi }}</b>
            </div>
            <div class="card kpi kpi--ok">
                <small>Tampil di landing</small>
                <b>{{ $aktif }}</b>
            </div>
            <div class="card kpi kpi--err">
                <small>Nonaktif</small>
                <b>{{ $nonaktif }}</b>
            </div>
        </div>

        <div class="pg">
            {{-- Daftar --}}
            <div class="card pg-main" style="gap: 0;">
                <div class="ch2">
                    <h2 style="margin: 0;">Daftar visi &amp; misi</h2>
                    <span class="faq-count">{{ $items->total() }} data</span>
                </div>

                @if ($items->isEmpty())
                    <div class="faq-empty">
                        <p class="faq-empty__title">
                            {{ request()->hasAny(['q', 'status', 'bagean']) ? 'Tidak ada hasil' : 'Belum ada visi/misi' }}
                        </p>
                        <p class="faq-empty__hint">
                            {{ request()->hasAny(['q', 'status', 'bagean']) ? 'Ubah kata kunci atau reset filter.' : 'Tambahkan satu visi dan satu misi agar halaman Profil terisi.' }}
                        </p>
                        <a href="{{ route('superadmin.visimisi.create') }}" class="btn">+ Tambah visi/misi</a>
                    </div>
                @else
                    <div class="tw">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 48px;">#</th>
                                    <th style="width: 100px;">Bagian</th>
                                    <th>Judul</th>
                                    <th>Isi / poin</th>
                                    <th style="width: 88px;">Urutan</th>
                                    <th style="width: 110px;">Status</th>
                                    <th style="width: 128px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    @php
                                        $poin = is_array($item->dhaptar)
                                            ? array_values(array_filter($item->dhaptar))
                                            : [];
                                    @endphp
                                    <tr>
                                        <td style="color: var(--mut);">{{ $items->firstItem() + $loop->index }}</td>
                                        <td>
                                            <span class="badge {{ $item->bagean === 'visi' ? '' : 'warn' }}">
                                                {{ $item->bagean === 'visi' ? 'Visi' : ($item->bagean === 'misi' ? 'Misi' : '—') }}
                                            </span>
                                        </td>
                                        <td
                                            style="font-weight: 600; white-space: normal; min-width: 160px; max-width: 240px;">
                                            {{ $item->judhul }}
                                        </td>
                                        <td
                                            style="color: var(--mut); white-space: normal; min-width: 240px; max-width: 420px;">
                                            @if ($item->bagean === 'misi' && count($poin))
                                                <strong style="color: var(--ink);">{{ count($poin) }} poin</strong>
                                                · {{ \Illuminate\Support\Str::limit($poin[0], 80) }}
                                            @else
                                                {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 110) }}
                                            @endif
                                        </td>
                                        <td>{{ $item->urutan }}</td>
                                        <td>
                                            <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                                                {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="row-actions" role="group" aria-label="Aksi">
                                                <a href="{{ route('superadmin.visimisi.edit', $item) }}" class="ia"
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
                                                    action="{{ route('superadmin.visimisi.toggle', $item) }}">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                        class="ia {{ $item->urip ? 'ia-mute' : 'ia-ok' }}"
                                                        title="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                        aria-label="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        @if ($item->urip)
                                                            <svg width="16" height="16" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                aria-hidden="true">
                                                                <path
                                                                    d="M17.94 17.94A10.9 10.9 0 0 1 12 20c-5 0-9.27-3.11-11-8 1-2.9 2.9-5.2 5.4-6.5" />
                                                                <path d="M1 1l22 22" />
                                                                <path
                                                                    d="M9.9 4.24A10.9 10.9 0 0 1 12 4c5 0 9.27 3.11 11 8a11.7 11.7 0 0 1-2.2 3.2" />
                                                                <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24" />
                                                            </svg>
                                                        @else
                                                            <svg width="16" height="16" viewBox="0 0 24 24"
                                                                fill="none" stroke="currentColor" stroke-width="2"
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
                                                    action="{{ route('superadmin.visimisi.destroy', $item) }}"
                                                    data-confirm="Hapus data ini?">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="ia ia-danger" title="Hapus"
                                                        aria-label="Hapus">
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

            {{-- Panel info kanan --}}
            <aside class="pg-side" aria-label="Informasi Visi dan Misi">
                <div class="card">
                    <h3>Yang tampil di landing</h3>
                    <div class="pv">
                        <span class="tag">Visi</span>
                        @if ($liveVisi)
                            <h4>{{ $liveVisi->judhul }}</h4>
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($liveVisi->isi), 200) }}</p>
                        @else
                            <p>Belum ada visi aktif.</p>
                        @endif
                    </div>
                    <div class="pv" style="margin-top: 12px;">
                        <span class="tag">Misi</span>
                        @if ($liveMisi)
                            <h4>{{ $liveMisi->judhul }}</h4>
                            @if (count($poin = $poinMisi))
                                <ol>
                                    @foreach (array_slice($poin, 0, 4) as $p)
                                        <li>{{ \Illuminate\Support\Str::limit($p, 90) }}</li>
                                    @endforeach
                                </ol>
                                @if (count($poin) > 4)
                                    <p style="margin-top: 8px;">+ {{ count($poin) - 4 }} poin lainnya</p>
                                @endif
                            @else
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($liveMisi->isi), 160) ?: 'Misi belum memiliki poin.' }}
                                </p>
                            @endif
                        @else
                            <p>Belum ada misi aktif.</p>
                        @endif
                    </div>
                    <a href="{{ url('/profil/visi-misi') }}" target="_blank" rel="noopener" class="btn gh sm"
                        style="margin-top: 14px; width: 100%;">Lihat halaman di situs</a>
                </div>

                <div class="card">
                    <h3>Tips pengelolaan</h3>
                    <ul class="tips">
                        <li>Cukup satu visi dan satu misi yang aktif pada satu waktu.</li>
                        <li>Revisi visi/misi? Buat versi baru, lalu nonaktifkan yang lama sebagai arsip.</li>
                        <li>Misi ditulis sebagai poin terpisah, satu kalimat per poin.</li>
                        <li>Angka urutan kecil tampil lebih dulu.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</x-dashboard.layout.app>
