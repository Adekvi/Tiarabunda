@if ($items->isEmpty())
    <div class="faq-empty">
        <div class="faq-empty__icon" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 4h14v16H5zM8 8h8M8 12h8M8 16h5" />
            </svg>
        </div>
        <p class="faq-empty__title">
            {{ $filtered ?? false ? 'Tidak ada berita yang cocok' : 'Belum ada berita' }}
        </p>
        <p class="faq-empty__desc">
            {{ $filtered ?? false ? 'Coba ubah kata kunci atau reset filter.' : 'Mulai dengan menulis berita pertama kampus.' }}
        </p>
        @if ($filtered ?? false)
            <a href="{{ route('superadmin.berita.index') }}" class="btn gh">Reset filter</a>
        @else
            <a href="{{ route('superadmin.berita.create') }}" class="btn">Tambah berita</a>
        @endif
    </div>
@else
    <div class="nw-scroll" role="region" aria-label="Daftar berita" tabindex="0">
        <table class="nw-table">
            <thead>
                <tr>
                    <th class="nw-col-nomor">No</th>
                    <th class="nw-col-berita">Berita</th>
                    <th class="nw-col-cat">Kategori</th>
                    <th class="nw-col-slug">Slug</th>
                    <th class="nw-col-author">Penulis</th>
                    <th class="nw-col-date">Tanggal terbit</th>
                    <th class="nw-col-order">Urutan</th>
                    <th class="nw-col-status">Status</th>
                    <th class="nw-col-updated">Diperbarui</th>
                    <th class="nw-col-aksi">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    @php
                        $terjadwal = $item->wektu_terbit && $item->wektu_terbit->isFuture();
                        if (!$item->urip) {
                            [$stCls, $stTxt] = ['err', 'Nonaktif'];
                        } elseif ($terjadwal) {
                            [$stCls, $stTxt] = ['warn', 'Terjadwal'];
                        } else {
                            [$stCls, $stTxt] = ['ok', 'Terbit'];
                        }
                        $golonganLabel = $item->golongan
                            ? \App\Models\Informasi\Berita::GOLONGAN[$item->golongan] ?? $item->golongan
                            : null;
                    @endphp
                    <tr>
                        <td class="nw-col-nomor">
                            {{ $items->firstItem() + $loop->index }}
                        </td>
                        <td class="nw-col-berita">
                            <div class="nw-item">
                                @if ($item->thumb_url)
                                    <img src="{{ $item->thumb_url }}" alt="" width="64" height="48"
                                        loading="lazy" decoding="async" class="nw-thumb">
                                @else
                                    <span class="nw-thumb nw-thumb--ph" aria-hidden="true">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M3 5h18v14H3zM3 16l5-5 4 4 3-3 6 6M9 9h.01" />
                                        </svg>
                                    </span>
                                @endif
                                <div class="nw-item__txt">
                                    <strong>{{ $item->judhul }}</strong>
                                    @if ($item->ringkesan)
                                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->ringkesan), 90) }}</p>
                                    @else
                                        <p class="nw-muted">Belum ada ringkasan</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="nw-col-cat">
                            @if ($golonganLabel)
                                <span class="nw-tag">{{ $golonganLabel }}</span>
                            @else
                                <span class="nw-muted">—</span>
                            @endif
                        </td>
                        <td class="nw-col-slug">
                            @if ($item->tandha)
                                <code class="nw-slug">{{ $item->tandha }}</code>
                            @else
                                <span class="nw-muted">—</span>
                            @endif
                        </td>
                        <td class="nw-col-author">
                            {{ $item->penulis?->name ?? '—' }}
                        </td>
                        <td class="nw-col-date">
                            @if ($item->wektu_terbit)
                                <span class="nw-date">{{ $item->wektu_terbit->format('d M Y') }}</span>
                                <small class="nw-rel">
                                    {{ $item->wektu_terbit->format('H:i') }} ·
                                    {{ $item->wektu_terbit->diffForHumans() }}
                                </small>
                            @else
                                <span class="nw-muted">—</span>
                            @endif
                        </td>
                        <td class="nw-col-order">
                            <span class="nw-order">{{ $item->urutan }}</span>
                        </td>
                        <td class="nw-col-status">
                            <span class="badge {{ $stCls }}">{{ $stTxt }}</span>
                        </td>
                        <td class="nw-col-updated">
                            @if ($item->updated_at)
                                <span class="nw-date">{{ $item->updated_at->format('d M Y') }}</span>
                                <small class="nw-rel">{{ $item->updated_at->diffForHumans() }}</small>
                            @else
                                <span class="nw-muted">—</span>
                            @endif
                        </td>
                        @if ($trashed ?? false)
                            <td class="nw-col-aksi">
                                <div class="row-actions" role="group" aria-label="Aksi berita terhapus">
                                    <form method="POST" action="{{ route('superadmin.berita.restore', $item->id) }}"
                                        class="ia-form">
                                        @csrf
                                        <button type="submit" class="ia ia-ok" title="Pulihkan"
                                            aria-label="Pulihkan berita"
                                            onclick="return confirm('Pulihkan berita ini?')">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" aria-hidden="true">
                                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                                                <path d="M3 3v5h5" />
                                            </svg>
                                        </button>
                                    </form>

                                    <form method="POST"
                                        action="{{ route('superadmin.berita.force-destroy', $item->id) }}"
                                        class="ia-form"
                                        onsubmit="return confirm('Hapus permanen? Tidak bisa dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ia ia-danger" title="Hapus permanen"
                                            aria-label="Hapus permanen">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" aria-hidden="true">
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
                        @else
                            <td class="nw-col-aksi">

                                <div class="row-actions" role="group" aria-label="Aksi berita">
                                    <a href="{{ route('superadmin.berita.edit', $item) }}" class="ia"
                                        title="Edit" aria-label="Edit berita">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path d="M12 20h9" />
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('superadmin.berita.toggle', $item) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="ia {{ $item->urip ? 'ia-mute' : 'ia-ok' }}"
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
                                    <form method="POST" action="{{ route('superadmin.berita.destroy', $item) }}"
                                        onsubmit="return confirm('Hapus berita ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="ia ia-danger" title="Hapus">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" aria-hidden="true">
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
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
