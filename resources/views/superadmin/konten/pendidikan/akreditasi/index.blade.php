<x-dashboard.layout.app title="Akreditasi" page-title="Akreditasi">
    <div class="g">
        @if (session('success'))
            <div class="al" role="status">{{ session('success') }}</div>
        @endif

        <div class="g g2" style="align-items:start;">
            <div class="g" style="min-width:0;">
                <div class="card">
                    <div class="ch2">
                        <div>
                            <h2 style="margin-bottom:4px;">Kelola Akreditasi</h2>
                            <p style="margin:0;font-size:0.85rem;color:var(--mut);">
                                Sertifikat dan status akreditasi institusi maupun program studi.
                            </p>
                        </div>
                        <a href="{{ route('superadmin.akreditasi.create') }}" class="btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                                <path d="M12 5v14" />
                                <path d="M5 12h14" />
                            </svg>
                            Tambah
                        </a>
                    </div>

                    <form method="GET" action="{{ route('superadmin.akreditasi.index') }}" class="faq-filter"
                        style="grid-template-columns:1fr minmax(120px,150px) minmax(120px,150px) auto;">
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
                                    placeholder="Judul atau status…" autocomplete="off">
                            </div>
                        </div>
                        <div class="f" style="margin-bottom:0;">
                            <label for="bagean">Jenis</label>
                            <select id="bagean" name="bagean">
                                <option value="">Semua</option>
                                <option value="institusi" @selected(request('bagean') === 'institusi')>Institusi</option>
                                <option value="prodi" @selected(request('bagean') === 'prodi')>Program studi</option>
                            </select>
                        </div>
                        <div class="f" style="margin-bottom:0;">
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
                                <a href="{{ route('superadmin.akreditasi.index') }}" class="btn gh">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">Daftar akreditasi</h2>
                        <span class="faq-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($items->isEmpty())
                        <div class="faq-empty">
                            <p class="faq-empty__title">Belum ada data akreditasi</p>
                            <p class="faq-empty__desc">Unggah sertifikat institusi atau per program studi.</p>
                            <a href="{{ route('superadmin.akreditasi.create') }}" class="btn">Tambah data</a>
                        </div>
                    @else
                        <div class="tw">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width:72px;">Gambar</th>
                                        <th>Judul</th>
                                        <th style="width:100px;">Jenis</th>
                                        <th>Program studi</th>
                                        <th style="width:100px;">Status</th>
                                        <th style="width:72px;">Urutan</th>
                                        <th style="width:100px;">Publikasi</th>
                                        <th style="width:128px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>
                                                @if ($item->gambar_url)
                                                    <img src="{{ $item->gambar_url }}" alt="" width="48"
                                                        height="48" loading="lazy" decoding="async"
                                                        style="width:48px;height:48px;object-fit:cover;border-radius:8px;border:1px solid var(--line);">
                                                @else
                                                    <span
                                                        style="display:inline-grid;place-items:center;width:48px;height:48px;border-radius:8px;background:var(--bg);color:var(--mut);font-size:0.7rem;">—</span>
                                                @endif
                                            </td>
                                            <td style="font-weight:600;white-space:normal;max-width:200px;">
                                                {{ $item->jeneng }}</td>
                                            <td>
                                                <span class="badge {{ $item->bagean === 'institusi' ? 'ok' : 'warn' }}">
                                                    {{ $item->bagean === 'institusi' ? 'Institusi' : 'Prodi' }}
                                                </span>
                                            </td>
                                            <td style="white-space:normal;">{{ $item->prodi?->jeneng ?? '—' }}</td>
                                            <td>{{ $item->status_akred ?: '—' }}</td>
                                            <td>{{ $item->urutan }}</td>
                                            <td>
                                                <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                                                    {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="row-actions" role="group">
                                                    <a href="{{ route('superadmin.akreditasi.edit', $item) }}"
                                                        class="ia" title="Edit">
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
                                                        action="{{ route('superadmin.akreditasi.toggle', $item) }}">
                                                        @csrf @method('PATCH')
                                                        <button type="submit"
                                                            class="ia {{ $item->urip ? 'ia-mute' : 'ia-ok' }}"
                                                            title="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                            @if ($item->urip)
                                                                <svg width="16" height="16"
                                                                    viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
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
                                                        action="{{ route('superadmin.akreditasi.destroy', $item) }}"
                                                        onsubmit="return confirm('Hapus data ini?');">
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

            <div class="g" style="gap:16px;">
                <div class="card">
                    <h3 style="margin-bottom:10px;">Tentang Akreditasi</h3>
                    <p style="margin:0;font-size:0.88rem;color:var(--mut);line-height:1.65;">
                        Tampilkan sertifikat institusi dan per program studi di halaman
                        <strong>Pendidikan → Akreditasi</strong>. Gunakan gambar jelas (scan/PDF diubah ke JPG/PNG).
                    </p>
                </div>
                <div class="card">
                    <h3 style="margin-bottom:14px;">Statistik</h3>
                    <div class="pmb-stats">
                        <div class="pmb-stat">
                            <span class="pmb-stat__val">{{ $stats['total'] }}</span>
                            <span class="pmb-stat__lbl">Total</span>
                        </div>
                        <div class="pmb-stat">
                            <span class="pmb-stat__val" style="color:var(--ok);">{{ $stats['aktif'] }}</span>
                            <span class="pmb-stat__lbl">Aktif</span>
                        </div>
                        <div class="pmb-stat">
                            <span class="pmb-stat__val">{{ $stats['institusi'] }}</span>
                            <span class="pmb-stat__lbl">Institusi</span>
                        </div>
                        <div class="pmb-stat">
                            <span class="pmb-stat__val">{{ $stats['prodi'] }}</span>
                            <span class="pmb-stat__lbl">Prodi</span>
                        </div>
                        <div class="pmb-stat" style="grid-column:1/-1;">
                            <span class="pmb-stat__val" style="color:var(--pri);">{{ $stats['ada_gambar'] }}</span>
                            <span class="pmb-stat__lbl">Sudah punya gambar sertifikat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.layout.app>
