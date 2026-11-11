{{-- Toolbar Sampah --}}
<div class="krr-trash-toolbar">
    <div class="krr-trash-toolbar__info">
        <span class="krr-trash-toolbar__title">Data terhapus</span>
        <span class="krr-trash-toolbar__desc">Pulihkan ke daftar aktif, atau hapus permanen.</span>
    </div>
    <button type="button" class="btn btn-danger btn-danger--outline" data-modal-open data-modal-type="danger"
        data-modal-title="Kosongkan sampah?"
        data-modal-message="SEMUA data di sampah akan dihapus permanen. Tindakan ini tidak dapat dibatalkan."
        data-modal-confirm="Ya, kosongkan" data-modal-action="{{ route('superadmin.karir.empty-trash') }}"
        data-modal-method="DELETE">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="margin-right:6px;">
            <path d="M3 6h18" />
            <path d="M8 6V4h8v2" />
            <path d="M19 6l-1 14H6L5 6" />
        </svg>
        Kosongkan sampah
    </button>
</div>

{{-- Tabel Sampah --}}
<div class="krr-table-wrap">
    <table class="krr-table krr-table--trashed">
        <thead>
            <tr>
                <th class="is-num">#</th>
                <th>Data Terhapus</th>
                <th class="is-time">Dihapus</th>
                <th class="is-actions">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td class="is-num">{{ $items->firstItem() + $loop->index }}</td>
                    <td>
                        <div class="krr-info">
                            <span class="krr-info__title">{{ $item->judhul }}</span>
                            @if ($item->isi)
                                <span class="krr-info__desc">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 90) }}
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="is-time">
                        @if ($item->deleted_at)
                            <div class="krr-trash-time">
                                <time datetime="{{ $item->deleted_at->toIso8601String() }}"
                                    title="{{ $item->deleted_at->translatedFormat('d M Y · H:i') }}">
                                    {{ $item->deleted_at->diffForHumans() }}
                                </time>
                                <small>{{ $item->deleted_at->translatedFormat('d M Y') }}</small>
                            </div>
                        @else
                            <span class="krr-muted">—</span>
                        @endif
                    </td>
                    <td class="is-actions">
                        <div class="krr-actions">
                            <button type="button" class="krr-act is-ok" title="Pulihkan" data-modal-open
                                data-modal-type="restore" data-modal-title="Pulihkan data?"
                                data-modal-message='Data "{{ $item->judhul }}" akan dikembalikan ke daftar aktif.'
                                data-modal-confirm="Ya, pulihkan"
                                data-modal-action="{{ route('superadmin.karir.restore', $item->id) }}"
                                data-modal-method="PATCH">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                                    <path d="M3 3v5h5" />
                                </svg>
                            </button>

                            <button type="button" class="krr-act is-danger" title="Hapus permanen" data-modal-open
                                data-modal-type="danger" data-modal-title="Hapus permanen?"
                                data-modal-message='Data "{{ $item->judhul }}" akan dihapus permanen dan tidak dapat dikembalikan.'
                                data-modal-confirm="Ya, hapus permanen"
                                data-modal-action="{{ route('superadmin.karir.force-delete', $item->id) }}"
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
