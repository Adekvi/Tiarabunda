@if ($items->isEmpty())
    <div class="pmb-empty">
        <div class="pmb-empty__ring" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18" />
                <path d="M8 6V4h8v2" />
                <path d="M19 6l-1 14H6L5 6" />
            </svg>
        </div>
        <h3 class="pmb-empty__title">Sampah kosong</h3>
        <p class="pmb-empty__desc">
            Tidak ada data yang dihapus. Item yang Anda hapus dari daftar akan muncul di sini.
        </p>
        <a href="{{ route('superadmin.pmb.index') }}" class="btn gh">
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
    <div class="pmb-trash-toolbar">
        <div class="pmb-trash-toolbar__info">
            <span class="pmb-trash-toolbar__title">Data terhapus</span>
            <span class="pmb-trash-toolbar__desc">Pulihkan ke daftar aktif, atau hapus permanen.</span>
        </div>
        <button type="button" class="btn btn-danger btn-danger--outline" data-modal-open data-modal-type="danger"
            data-modal-title="Kosongkan sampah?"
            data-modal-message="SEMUA data di sampah akan dihapus permanen. Tindakan ini tidak dapat dibatalkan."
            data-modal-confirm="Ya, kosongkan" data-modal-action="{{ route('superadmin.pmb.empty-trash') }}"
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
    <div class="pmb-table-wrap">
        <table class="pmb-table pmb-table--trashed">
            <thead>
                <tr>
                    <th class="is-num" width="50">#</th>
                    <th>Data Jalur</th>
                    <th width="180">Dihapus</th>
                    <th class="is-actions" width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td class="is-num">{{ $items->firstItem() + $loop->index }}</td>

                        <td>
                            <div class="pmb-name" style="flex-direction:column;align-items:flex-start;gap:6px;">
                                <span class="pmb-name__main">{{ $item->jeneng }}</span>
                                @if ($item->tandha)
                                    <code class="pmb-code">{{ $item->tandha }}</code>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if ($item->deleted_at)
                                <div class="pmb-trash-time">
                                    <time datetime="{{ $item->deleted_at->toIso8601String() }}"
                                        title="{{ $item->deleted_at->translatedFormat('d M Y · H:i') }}">
                                        {{ $item->deleted_at->diffForHumans() }}
                                    </time>
                                    <small>{{ $item->deleted_at->translatedFormat('d M Y') }}</small>
                                </div>
                            @else
                                <span style="color:var(--mut);">—</span>
                            @endif
                        </td>

                        <td class="is-actions">
                            <div class="pmb-actions">
                                {{-- Restore --}}
                                <form method="POST" action="{{ route('superadmin.pmb.restore', $item->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="pmb-act is-ok" title="Pulihkan"
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
                                <button type="button" class="pmb-act is-danger" title="Hapus permanen"
                                    aria-label="Hapus permanen {{ $item->jeneng }}" data-modal-open
                                    data-modal-type="danger" data-modal-title="Hapus permanen?"
                                    data-modal-message='Jalur "{{ $item->jeneng }}" akan dihapus permanen dan tidak dapat dikembalikan.'
                                    data-modal-confirm="Ya, hapus permanen"
                                    data-modal-action="{{ route('superadmin.pmb.force-delete', $item->id) }}"
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
        @include('superadmin.konten.pendidikan.pmb.partials.paginate', ['items' => $items])
    </div>
@endif
