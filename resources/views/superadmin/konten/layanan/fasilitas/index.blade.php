<x-dashboard.layout.app title="Fasilitas" page-title="Fasilitas">
    <div class="g">
        @if (session('success'))
            <div class="al" role="status">{{ session('success') }}</div>
        @endif

        <div class="g g2" style="align-items:start;">
            <div class="g" style="min-width:0;">
                <div class="card">
                    <div class="ch2">
                        <div>
                            <h2 style="margin-bottom:4px;">Kelola Fasilitas</h2>
                            <p style="margin:0;font-size:0.85rem;color:var(--mut);">
                                Galeri fasilitas kampus. Gambar dioptimasi saat unggah agar halaman tetap cepat.
                            </p>
                        </div>
                        <a href="{{ route('superadmin.fasilitas.create') }}" class="btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                                <path d="M12 5v14" />
                                <path d="M5 12h14" />
                            </svg>
                            Tambah
                        </a>
                    </div>

                    <form method="GET" action="{{ route('superadmin.fasilitas.index') }}" class="faq-filter">
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
                                    placeholder="Nama fasilitas…" autocomplete="off">
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
                                <a href="{{ route('superadmin.fasilitas.index') }}" class="btn gh">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="ch2">
                        <h2 style="margin:0;">Daftar fasilitas</h2>
                        <span class="faq-count">{{ $items->total() }} data</span>
                    </div>

                    @if ($items->isEmpty())
                        <div class="faq-empty">
                            <p class="faq-empty__title">Belum ada fasilitas</p>
                            <a href="{{ route('superadmin.fasilitas.create') }}" class="btn">Tambah fasilitas</a>
                        </div>
                    @else
                        <div class="fas-grid">
                            @foreach ($items as $item)
                                <article class="fas-card">
                                    <div class="fas-card__img">
                                        @if ($item->thumb_url)
                                            <img src="{{ $item->thumb_url }}" alt="" width="320"
                                                height="200" loading="lazy" decoding="async">
                                        @else
                                            <div class="fas-card__ph">Tanpa gambar</div>
                                        @endif
                                        <span class="badge {{ $item->urip ? 'ok' : 'err' }} fas-card__badge">
                                            {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                    <div class="fas-card__body">
                                        <h3>{{ $item->jeneng }}</h3>
                                        <p class="fas-card__meta">Urutan {{ $item->urutan }}</p>
                                        <div class="row-actions" style="margin-top:10px;">
                                            <a href="{{ route('superadmin.fasilitas.edit', $item) }}" class="ia"
                                                title="Edit">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M12 20h9" />
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                                </svg>
                                            </a>
                                            <form method="POST"
                                                action="{{ route('superadmin.fasilitas.toggle', $item) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="ia {{ $item->urip ? 'ia-mute' : 'ia-ok' }}"
                                                    title="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }}">
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
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                                            <circle cx="12" cy="12" r="3" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('superadmin.fasilitas.destroy', $item) }}"
                                                onsubmit="return confirm('Hapus fasilitas ini?');">
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
                                    </div>
                                </article>
                            @endforeach
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
                    <h3 style="margin-bottom:10px;">Tentang fasilitas</h3>
                    <p style="margin:0;font-size:0.88rem;color:var(--mut);line-height:1.65;">
                        Gambar di-resize saat unggah (utama ≤1600px, thumb ≤480px).
                        Grid memakai <strong>thumb</strong> + lazy load agar admin dan landing tidak memuat file besar
                        sekaligus.
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
                            <span class="pmb-stat__val" style="color:var(--err);">{{ $stats['nonaktif'] }}</span>
                            <span class="pmb-stat__lbl">Nonaktif</span>
                        </div>
                        <div class="pmb-stat">
                            <span class="pmb-stat__val" style="color:var(--pri);">{{ $stats['ada_gambar'] }}</span>
                            <span class="pmb-stat__lbl">Punya gambar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <style>
            .fas-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 14px;
            }

            .fas-card {
                border: 1px solid var(--line);
                border-radius: 12px;
                overflow: hidden;
                background: var(--card);
            }

            .fas-card__img {
                position: relative;
                aspect-ratio: 16 / 10;
                background: var(--bg);
            }

            .fas-card__img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            .fas-card__ph {
                display: grid;
                place-items: center;
                height: 100%;
                color: var(--mut);
                font-size: .8rem;
            }

            .fas-card__badge {
                position: absolute;
                top: 8px;
                left: 8px;
            }

            .fas-card__body {
                padding: 12px 14px 14px;
            }

            .fas-card__body h3 {
                margin: 0;
                font-size: .95rem;
                line-height: 1.35;
            }

            .fas-card__meta {
                margin: 4px 0 0;
                font-size: .78rem;
                color: var(--mut);
            }
        </style>
    @endpush
</x-dashboard.layout.app>
