@if ($items->isEmpty())
    <div class="empty-state">
        <div class="empty-state__visual" aria-hidden="true">
            <span class="empty-state__ring">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18" />
                    <path d="M8 6V4h8v2" />
                    <path d="M19 6l-1 14H6L5 6" />
                </svg>
            </span>
        </div>
        <h3 class="empty-state__title">Sampah kosong</h3>
        <p class="empty-state__desc">
            Tidak ada fakultas yang dihapus. Item yang kamu hapus dari daftar akan muncul di sini.
        </p>
        <a href="{{ route('superadmin.fakultas.index') }}" class="btn gh">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" aria-hidden="true">
                <path d="M19 12H5" />
                <path d="M12 19l-7-7 7-7" />
            </svg>
            Kembali ke daftar
        </a>
    </div>
@else
    <div class="trash-toolbar">
        <div class="trash-toolbar__info">
            <span class="trash-toolbar__count">{{ $items->total() }} fakultas</span>
            <span class="trash-toolbar__hint">
                Pulihkan ke daftar aktif, atau hapus permanen. Prodi terkait tidak ikut terhapus otomatis.
            </span>
        </div>

        {{-- Bukan submit langsung --}}
        <button type="button" class="btn btn-danger btn-danger--outline" data-modal-open data-modal-type="danger"
            data-modal-title="Kosongkan sampah?"
            data-modal-message="SEMUA fakultas di sampah akan dihapus permanen. Tindakan ini tidak dapat dibatalkan."
            data-modal-confirm="Ya, kosongkan" data-modal-action="{{ route('superadmin.fakultas.empty-trash') }}"
            data-modal-method="DELETE">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" aria-hidden="true">
                <path d="M3 6h18" />
                <path d="M8 6V4h8v2" />
                <path d="M19 6l-1 14H6L5 6" />
            </svg>
            Kosongkan sampah
        </button>
    </div>

    <div class="fk-table-wrap">
        <table class="fk-table fk-table--trashed">
            <caption class="visually-hidden">Daftar fakultas yang dihapus</caption>
            <colgroup>
                <col style="width: 56px">
                <col>
                <col style="width: 160px">
                <col style="width: 120px">
            </colgroup>
            <thead>
                <tr>
                    <th scope="col" class="is-num">#</th>
                    <th scope="col">Fakultas</th>
                    <th scope="col">Dihapus</th>
                    <th scope="col" class="is-actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td class="is-num">
                            <span class="fk-num">{{ $items->firstItem() + $loop->index }}</span>
                        </td>

                        <td>
                            <div class="fk-main">
                                <span class="fk-name">{{ $item->jeneng }}</span>
                                @if ($item->kodhe ?? ($item->tandha ?? null))
                                    <code class="fk-code">{{ $item->kodhe ?? $item->tandha }}</code>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if ($item->deleted_at)
                                <time class="fk-time" datetime="{{ $item->deleted_at->toIso8601String() }}"
                                    title="{{ $item->deleted_at->translatedFormat('d M Y · H:i') }}">
                                    {{ $item->deleted_at->diffForHumans() }}
                                </time>
                            @else
                                <span class="fk-time is-muted">—</span>
                            @endif
                        </td>

                        <td class="is-actions">
                            <div class="fk-actions" role="group" aria-label="Aksi {{ $item->jeneng }}">
                                {{-- RESTORE: POST saja --}}
                                <form method="POST" action="{{ route('superadmin.fakultas.restore', $item->id) }}">
                                    @csrf
                                    <button type="submit" class="fk-btn fk-btn--ok" title="Pulihkan"
                                        aria-label="Pulihkan {{ $item->jeneng }}">
                                        {{-- ikon --}}
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                                            <path d="M3 3v5h5" />
                                        </svg>
                                    </button>
                                </form>

                                {{-- FORCE DELETE: lewat modal --}}
                                <button type="button" class="fk-btn fk-btn--danger" title="Hapus permanen"
                                    aria-label="Hapus permanen {{ $item->jeneng }}" data-modal-open
                                    data-modal-type="danger" data-modal-title="Hapus permanen?"
                                    data-modal-message='Fakultas "{{ $item->jeneng }}" akan dihapus permanen.'
                                    data-modal-confirm="Ya, hapus permanen"
                                    data-modal-action="{{ route('superadmin.fakultas.force-delete', $item->id) }}"
                                    data-modal-method="DELETE">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M3 6h18" />
                                        <path d="M8 6V4h8v2" />
                                        <path d="M19 6l-1 14H6L5 6" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
