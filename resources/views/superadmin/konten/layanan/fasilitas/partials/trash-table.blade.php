@if ($items->isEmpty())
    <div class="faq-empty">
        <div class="faq-empty__icon" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18" />
                <path d="M8 6V4h8v2" />
                <path d="M19 6l-1 14H6L5 6" />
            </svg>
        </div>
        <p class="faq-empty__title">Sampah kosong</p>
        <p class="faq-empty__desc">Tidak ada fasilitas yang dihapus.</p>
        <a href="{{ route('superadmin.fasilitas.index') }}" class="btn gh">Kembali ke daftar</a>
    </div>
@else
    <div class="trash-actions-bar">
        <form method="POST" action="{{ route('superadmin.fasilitas.empty-trash') }}" data-confirm
            data-confirm-title="Kosongkan sampah?"
            data-confirm-message="SEMUA fasilitas di sampah akan dihapus permanen beserta file gambarnya. Tindakan ini tidak dapat dibatalkan."
            data-confirm-ok="Ya, kosongkan" data-confirm-tone="danger">
            @csrf
            <button type="submit" class="btn btn-danger">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round">
                    <path d="M3 6h18" />
                    <path d="M8 6V4h8v2" />
                    <path d="M19 6l-1 14H6L5 6" />
                </svg>
                Kosongkan sampah
            </button>
        </form>
    </div>

    <div class="fas-table-wrap">
        <table class="fas-table fas-table--trashed">
            <caption class="visually-hidden">Daftar fasilitas yang dihapus</caption>

            <colgroup>
                <col class="fas-col-thumb">
                <col class="fas-col-main">
                <col class="fas-col-updated">
                <col class="fas-col-actions">
            </colgroup>

            <thead>
                <tr>
                    <th scope="col"><span class="visually-hidden">Gambar</span></th>
                    <th scope="col">Fasilitas</th>
                    <th scope="col">Dihapus</th>
                    <th scope="col" class="is-actions"><span class="visually-hidden">Aksi</span></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td class="fas-cell-thumb">
                            <div class="fas-thumb fas-thumb--trashed">
                                @if ($item->thumb_url)
                                    <img src="{{ $item->thumb_url }}" alt="" width="64" height="48"
                                        loading="lazy" decoding="async">
                                @else
                                    <span class="fas-thumb__ph" aria-hidden="true">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <path d="m21 15-5-5L5 21" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="fas-cell-main">
                            <div class="fas-cell-main__body">
                                <span class="fas-name">{{ $item->jeneng }}</span>
                                @if ($item->tandha)
                                    <code class="fas-slug">/{{ $item->tandha }}</code>
                                @endif
                            </div>
                        </td>

                        <td class="fas-cell-updated">
                            @if ($item->deleted_at)
                                <span class="fas-time" title="{{ $item->deleted_at->translatedFormat('d M Y · H:i') }}">
                                    {{ $item->deleted_at->diffForHumans(short: true) }}
                                </span>
                            @else
                                <span class="fas-time fas-time--muted">—</span>
                            @endif
                        </td>

                        <td class="fas-cell-actions">
                            <div class="fas-actions" role="group" aria-label="Aksi untuk {{ $item->jeneng }}">
                                <form method="POST" action="{{ route('superadmin.fasilitas.restore', $item->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="fa-btn fa-btn--ok" title="Pulihkan"
                                        aria-label="Pulihkan {{ $item->jeneng }}">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                                            <path d="M3 3v5h5" />
                                        </svg>
                                    </button>
                                </form>

                                <form method="POST"
                                    action="{{ route('superadmin.fasilitas.force-delete', $item->id) }}" data-confirm
                                    data-confirm-title="Hapus permanen?"
                                    data-confirm-message="Fasilitas &quot;{{ $item->jeneng }}&quot; akan dihapus PERMANEN beserta file gambarnya. Tindakan ini tidak dapat dibatalkan."
                                    data-confirm-ok="Ya, hapus permanen" data-confirm-tone="danger">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="fa-btn fa-btn--danger" title="Hapus permanen"
                                        aria-label="Hapus permanen {{ $item->jeneng }}">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
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
@endif
