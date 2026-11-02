<x-dashboard.layout.app title="Beasiswa" page-title="Beasiswa">
    <div class="g">
        @if (session('success'))
            <div class="al" role="status">{{ session('success') }}</div>
        @endif

        <div class="g g2" style="align-items:start;">
            <div class="g" style="min-width:0;">
                <div class="card">
                    <div class="ch2">
                        <div>
                            <h2 style="margin-bottom:4px;">Kelola Beasiswa</h2>
                            <p style="margin:0;font-size:0.85rem;color:var(--mut);">
                                Informasi beasiswa untuk calon dan mahasiswa yang tampil di situs publik.
                            </p>
                        </div>
                        <a href="{{ route('superadmin.beasiswa.create') }}" class="btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                                <path d="M12 5v14" />
                                <path d="M5 12h14" />
                            </svg>
                            Tambah
                        </a>
                    </div>

                    <form method="GET" action="{{ route('superadmin.beasiswa.index') }}" class="faq-filter">
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
                                    placeholder="Cari nama, cakupan, atau deskripsi…" autocomplete="off">
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
                                <a href="{{ route('superadmin.beasiswa.index') }}" class="btn gh">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">Daftar beasiswa</h2>
                        <span class="faq-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($items->isEmpty())
                        <div class="faq-empty">
                            <p class="faq-empty__title">Belum ada beasiswa</p>
                            <p class="faq-empty__desc">Tambahkan skema beasiswa agar tampil di halaman publik.</p>
                            <a href="{{ route('superadmin.beasiswa.create') }}" class="btn">Tambah beasiswa</a>
                        </div>
                    @else
                        <div class="tw">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width:48px;">#</th>
                                        <th>Nama</th>
                                        <th>Cakupan</th>
                                        <th style="width:80px;">Urutan</th>
                                        <th style="width:100px;">Status</th>
                                        <th style="width:128px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td style="color:var(--mut);">{{ $items->firstItem() + $loop->index }}</td>
                                            <td style="font-weight:600;white-space:normal;max-width:240px;">
                                                {{ $item->jeneng }}
                                            </td>
                                            <td style="color:var(--mut);white-space:normal;max-width:220px;">
                                                {{ $item->jangkepan ?: '—' }}
                                            </td>
                                            <td>{{ $item->urutan }}</td>
                                            <td>
                                                <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                                                    {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="row-actions" role="group">
                                                    <a href="{{ route('superadmin.beasiswa.edit', $item) }}"
                                                        class="ia" title="Edit" aria-label="Edit">
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
                                                        action="{{ route('superadmin.beasiswa.toggle', $item) }}">
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
                                                        action="{{ route('superadmin.beasiswa.destroy', $item) }}"
                                                        onsubmit="return confirm('Hapus beasiswa ini?');">
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
                    <h3 style="margin-bottom:10px;">Tentang Beasiswa</h3>
                    <p style="margin:0;font-size:0.88rem;color:var(--mut);line-height:1.65;">
                        Data aktif tampil di <strong>Pendidikan → Beasiswa</strong> pada situs publik.
                        Cantumkan cakupan dan persyaratan agar calon penerima mudah memahami.
                    </p>
                </div>

                <div class="card">
                    <h3 style="margin-bottom:14px;">Statistik beasiswa</h3>
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
                            <span class="pmb-stat__val" style="color:var(--err);">{{ $stats['nonaktif'] }}</span>
                            <span class="pmb-stat__lbl">Nonaktif</span>
                        </div>
                        <div class="pmb-stat">
                            <span class="pmb-stat__val"
                                style="color:var(--pri);">{{ $stats['dengan_syarat'] }}</span>
                            <span class="pmb-stat__lbl">Ada persyaratan</span>
                        </div>
                        <div class="pmb-stat" style="grid-column:1 / -1;">
                            <span class="pmb-stat__val">{{ $stats['dengan_cakupan'] }}</span>
                            <span class="pmb-stat__lbl">Sudah diisi cakupan bantuan</span>
                        </div>
                    </div>
                    <p style="margin:12px 0 0;font-size:0.75rem;color:var(--mut);">
                        Dihitung dari seluruh data. Diperbarui setiap muat ulang halaman.
                    </p>
                </div>

                <div class="card">
                    <h3 style="margin-bottom:10px;">Saran pengisian</h3>
                    <ul style="margin:0;padding-left:18px;color:var(--mut);font-size:0.88rem;line-height:1.7;">
                        <li>Satu skema = satu entri (KIP, prestasi, alumni, dll.).</li>
                        <li>Cakupan: biaya apa saja yang ditanggung.</li>
                        <li>Persyaratan ditulis jelas, bisa multi-paragraf.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.layout.app>
