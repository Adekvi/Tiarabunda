<x-dashboard.layout.app title="Kegiatan" page-title="Kegiatan Kampus">
    <div class="g">
        @if (session('success'))
            <div class="al" role="status">{{ session('success') }}</div>
        @endif

        <div class="g g2" style="align-items:start;">
            <div class="g" style="min-width:0;">
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

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">Daftar kegiatan</h2>
                        <span class="faq-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($items->isEmpty())
                        <div class="faq-empty">
                            <p class="faq-empty__title">Belum ada kegiatan</p>
                            <p class="faq-empty__desc">Tambahkan agenda kampus agar tampil di situs publik.</p>
                            <a href="{{ route('superadmin.kegiatan.create') }}" class="btn">Tambah kegiatan</a>
                        </div>
                    @else
                        <div class="tw">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width:64px;">Foto</th>
                                        <th>Judul</th>
                                        <th style="width:140px;">Lokasi</th>
                                        <th style="width:150px;">Jadwal</th>
                                        <th style="width:100px;">Status</th>
                                        <th style="width:128px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>
                                                @if ($item->thumb_url)
                                                    <img src="{{ $item->thumb_url }}" alt="" width="48"
                                                        height="48" loading="lazy" decoding="async"
                                                        style="width:48px;height:48px;object-fit:cover;border-radius:8px;border:1px solid var(--line);">
                                                @else
                                                    <span
                                                        style="display:inline-grid;place-items:center;width:48px;height:48px;border-radius:8px;background:var(--bg);color:var(--mut);font-size:0.7rem;">—</span>
                                                @endif
                                            </td>
                                            <td style="white-space:normal;max-width:280px;">
                                                <strong>{{ $item->judhul }}</strong>
                                                @if ($item->ringkesan)
                                                    <div style="color:var(--mut);font-size:0.82rem;margin-top:2px;">
                                                        {{ \Illuminate\Support\Str::limit($item->ringkesan, 70) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td style="white-space:normal;font-size:0.85rem;color:var(--mut);">
                                                {{ $item->lokasi ?: '—' }}
                                            </td>
                                            <td style="font-size:0.85rem;color:var(--mut);white-space:normal;">
                                                @if ($item->wektu_mulai)
                                                    {{ $item->wektu_mulai->format('d M Y H:i') }}
                                                    @if ($item->wektu_selesai)
                                                        <br>– {{ $item->wektu_selesai->format('d M Y H:i') }}
                                                    @endif
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                                                    {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="row-actions" role="group">
                                                    <a href="{{ route('superadmin.kegiatan.edit', $item) }}"
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
                                                        action="{{ route('superadmin.kegiatan.toggle', $item) }}">
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
                                                        action="{{ route('superadmin.kegiatan.destroy', $item) }}"
                                                        onsubmit="return confirm('Hapus kegiatan ini?');">
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
                    <h3 style="margin-bottom:10px;">Tentang Kegiatan</h3>
                    <p style="margin:0;font-size:0.88rem;color:var(--mut);line-height:1.65;">
                        Konten aktif tampil di <strong>Informasi → Kegiatan Kampus</strong>.
                        Isi jadwal dan lokasi agar pengunjung mudah merencanakan kehadiran.
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
                            <span class="pmb-stat__val" style="color:var(--pri);">{{ $stats['mendatang'] }}</span>
                            <span class="pmb-stat__lbl">Mendatang</span>
                        </div>
                        <div class="pmb-stat">
                            <span class="pmb-stat__val" style="color:#c05f0a;">{{ $stats['berlangsung'] }}</span>
                            <span class="pmb-stat__lbl">Berlangsung</span>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <h3 style="margin-bottom:10px;">Saran</h3>
                    <ul style="margin:0;padding-left:18px;color:var(--mut);font-size:0.88rem;line-height:1.7;">
                        <li>Satu entri = satu acara / rangkaian jelas.</li>
                        <li>Poster dioptimasi otomatis saat unggah.</li>
                        <li>Nonaktifkan acara yang sudah usang tanpa menghapus.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.layout.app>
