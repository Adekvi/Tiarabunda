<x-dashboard.layout.app title="Visi & Misi" page-title="Visi & Misi">
    <div class="g">
        @if (session('success'))
            <div class="al" role="status">{{ session('success') }}</div>
        @endif

        <div class="g g2" style="align-items:start;">
            <div class="g" style="min-width:0;">
                <div class="card">
                    <div class="ch2">
                        <div>
                            <h2 style="margin-bottom:4px;">Kelola Visi &amp; Misi</h2>
                            <p style="margin:0;font-size:0.85rem;color:var(--mut);">
                                Arah dan tujuan institusi di menu Profil → Visi &amp; Misi.
                            </p>
                        </div>
                        <a href="{{ route('superadmin.visimisi.create') }}" class="btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                                <path d="M12 5v14" />
                                <path d="M5 12h14" />
                            </svg>
                            Tambah
                        </a>
                    </div>

                    <form method="GET" action="{{ route('superadmin.visimisi.index') }}" class="faq-filter"
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
                                    placeholder="Cari judul atau isi…" autocomplete="off">
                            </div>
                        </div>
                        <div class="f" style="margin-bottom:0;">
                            <label for="bagean">Bagian</label>
                            <select id="bagean" name="bagean">
                                <option value="">Semua</option>
                                <option value="visi" @selected(request('bagean') === 'visi')>Visi</option>
                                <option value="misi" @selected(request('bagean') === 'misi')>Misi</option>
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
                                <a href="{{ route('superadmin.visimisi.index') }}" class="btn gh">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">Daftar</h2>
                        <span class="faq-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($items->isEmpty())
                        <div class="faq-empty">
                            <p class="faq-empty__title">Belum ada visi atau misi</p>
                            <p class="faq-empty__desc">Tambahkan visi (narasi) dan misi (daftar poin) secara terpisah.
                            </p>
                            <a href="{{ route('superadmin.visimisi.create') }}" class="btn">Tambah data</a>
                        </div>
                    @else
                        <div class="tw">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width:48px;">#</th>
                                        <th style="width:90px;">Bagian</th>
                                        <th>Judul</th>
                                        <th>Ringkasan</th>
                                        <th style="width:80px;">Urutan</th>
                                        <th style="width:100px;">Status</th>
                                        <th style="width:128px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td style="color:var(--mut);">{{ $items->firstItem() + $loop->index }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ ($item->bagean ?? '') === 'visi' ? 'ok' : 'warn' }}">
                                                    {{ ($item->bagean ?? '') === 'visi' ? 'Visi' : 'Misi' }}
                                                </span>
                                            </td>
                                            <td style="font-weight:600;white-space:normal;max-width:200px;">
                                                {{ $item->judhul }}</td>
                                            <td style="color:var(--mut);white-space:normal;max-width:280px;">
                                                @if (($item->bagean ?? '') === 'misi' && is_array($item->dhaptar) && count($item->dhaptar))
                                                    {{ count($item->dhaptar) }} poin
                                                @else
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 80) }}
                                                @endif
                                            </td>
                                            <td>{{ $item->urutan }}</td>
                                            <td>
                                                <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                                                    {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="row-actions" role="group">
                                                    <a href="{{ route('superadmin.visimisi.edit', $item) }}"
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
                                                        action="{{ route('superadmin.visimisi.toggle', $item) }}">
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
                                                        action="{{ route('superadmin.visimisi.destroy', $item) }}"
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
                    <h3 style="margin-bottom:10px;">Tentang halaman ini</h3>
                    <p style="margin:0;font-size:0.88rem;color:var(--mut);line-height:1.65;">
                        Tampil di <strong>Profil → Visi &amp; Misi</strong>.
                        Disarankan: satu entri <strong>Visi</strong> (narasi) dan satu atau beberapa entri
                        <strong>Misi</strong> (poin-poin).
                    </p>
                </div>
                <div class="card">
                    <h3 style="margin-bottom:10px;">Perbedaan Visi &amp; Misi</h3>
                    <ul style="margin:0;padding-left:18px;color:var(--mut);font-size:0.88rem;line-height:1.7;">
                        <li><strong>Visi</strong> — satu paragraf tujuan jangka panjang.</li>
                        <li><strong>Misi</strong> — daftar langkah/komitmen (satu baris satu poin).</li>
                    </ul>
                </div>
                <div class="card">
                    <h3 style="margin-bottom:10px;">Ringkasan</h3>
                    <div style="display:grid;gap:10px;font-size:0.88rem;">
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:var(--mut);">Total data</span>
                            <strong>{{ $items->total() }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.layout.app>
