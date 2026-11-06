@if ($items->isEmpty())
    <div class="beasiswa-empty">
        <div class="beasiswa-empty__ring" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18" />
                <path d="M8 6V4h8v2" />
                <path d="M19 6l-1 14H6L5 6" />
            </svg>
        </div>
        <h3 class="beasiswa-empty__title">Sampah kosong</h3>
        <p class="beasiswa-empty__desc">
            Tidak ada data yang dihapus. Item yang Anda hapus dari daftar akan muncul di sini.
        </p>
        <a href="{{ route('superadmin.beasiswa.index') }}" class="btn gh">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.4" stroke-linecap="round" aria-hidden="true">
                <path d="M19 12H5" />
                <path d="M12 19l-7-7 7-7" />
            </svg>
            Kembali ke daftar
        </a>
    </div>
@else
    {{-- Toolbar Sampah --}}
    <div class="beasiswa-trash-toolbar"
        style="display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 16px 20px; margin-bottom: 20px; border-radius: 12px; background: color-mix(in srgb, var(--err) 6%, var(--card)); border: 1px solid color-mix(in srgb, var(--err) 20%, var(--line));">
        <div>
            <span style="font-size: 0.9rem; font-weight: 800; color: var(--err); display: block;">Data terhapus</span>
            <span style="font-size: 0.8rem; color: var(--mut);">Pulihkan ke daftar aktif, atau hapus permanen.</span>
        </div>
        <button type="button" class="btn btn-danger btn-danger--outline" data-modal-open data-modal-type="danger"
            data-modal-title="Kosongkan sampah?"
            data-modal-message="SEMUA data di sampah akan dihapus permanen. Tindakan ini tidak dapat dibatalkan."
            data-modal-confirm="Ya, kosongkan" data-modal-action="{{ route('superadmin.beasiswa.empty-trash') }}"
            data-modal-method="DELETE">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="margin-right: 6px;">
                <path d="M3 6h18" />
                <path d="M8 6V4h8v2" />
                <path d="M19 6l-1 14H6L5 6" />
            </svg>
            Kosongkan sampah
        </button>
    </div>

    {{-- Tabel Sampah --}}
    <div class="beasiswa-table-wrap">
        <table class="beasiswa-table">
            <thead>
                <tr>
                    <th class="is-num" width="50">#</th>
                    <th>Data Beasiswa</th>
                    <th width="180">Dihapus</th>
                    <th class="is-actions" width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td class="is-num">{{ $items->firstItem() + $loop->index }}</td>

                        <td>
                            <div class="beasiswa-name" style="flex-direction:column;align-items:flex-start;gap:6px;">
                                <span class="beasiswa-name__main">{{ $item->jeneng }}</span>
                                @if ($item->tandha)
                                    <code class="beasiswa-code">{{ $item->tandha }}</code>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if ($item->deleted_at)
                                <div class="beasiswa-time" style="display: flex; flex-direction: column; gap: 2px;">
                                    <time datetime="{{ $item->deleted_at->toIso8601String() }}"
                                        style="font-size: 0.85rem; font-weight: 600; color: var(--ink);"
                                        title="{{ $item->deleted_at->translatedFormat('d M Y · H:i') }}">
                                        {{ $item->deleted_at->diffForHumans() }}
                                    </time>
                                    <small
                                        style="font-size: 0.75rem; color: var(--mut);">{{ $item->deleted_at->translatedFormat('d M Y') }}</small>
                                </div>
                            @else
                                <span style="color:var(--mut);">—</span>
                            @endif
                        </td>

                        <td class="is-actions">
                            <div class="beasiswa-actions">
                                {{-- Restore --}}
                                <form method="POST" action="{{ route('superadmin.beasiswa.restore', $item->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="beasiswa-act is-ok" title="Pulihkan"
                                        aria-label="Pulihkan {{ $item->jeneng }}">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                                            <path d="M3 3v5h5" />
                                        </svg>
                                    </button>
                                </form>

                                {{-- Force Delete --}}
                                <button type="button" class="beasiswa-act is-danger" title="Hapus permanen"
                                    aria-label="Hapus permanen {{ $item->jeneng }}" data-modal-open
                                    data-modal-type="danger" data-modal-title="Hapus permanen?"
                                    data-modal-message='Beasiswa "{{ $item->jeneng }}" akan dihapus permanen dan tidak dapat dikembalikan.'
                                    data-modal-confirm="Ya, hapus permanen"
                                    data-modal-action="{{ route('superadmin.beasiswa.force-delete', $item->id) }}"
                                    data-modal-method="DELETE">
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
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        @include('superadmin.konten.pendidikan.beasiswa.partials.paginate', ['items' => $items])
    </div>
@endif
