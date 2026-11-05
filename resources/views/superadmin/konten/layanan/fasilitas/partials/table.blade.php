@if ($items->isEmpty())
    <div class="faq-empty">
        <div class="faq-empty__icon" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="16" rx="2" />
                <path d="M3 10h18M8 4v16" />
            </svg>
        </div>
        <p class="faq-empty__title">Belum ada fasilitas</p>
        <p class="faq-empty__desc">
            Tambahkan fasilitas pertama Anda. Gambar akan dioptimasi otomatis saat disimpan.
        </p>
        <a href="{{ route('superadmin.fasilitas.create') }}" class="btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.2" stroke-linecap="round">
                <path d="M12 5v14M5 12h14" />
            </svg>
            Tambah fasilitas
        </a>
    </div>
@else
    <div class="fas-table-wrap">
        <table class="fas-table">
            <caption class="visually-hidden">Daftar fasilitas kampus</caption>

            <colgroup>
                <col class="fas-col-no">
                <col class="fas-col-thumb">
                <col class="fas-col-main">
                <col class="fas-col-status">
                <col class="fas-col-order">
                <col class="fas-col-updated">
                <col class="fas-col-actions">
            </colgroup>

            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Gambar</th>
                    <th scope="col">Fasilitas</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="is-num">Urutan</th>
                    <th scope="col">Diperbarui</th>
                    <th scope="col" class="is-actions">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td class="fas-cell-no">
                            {{ $items->firstItem() + $loop->index }}
                        </td>
                        {{-- Thumbnail --}}
                        <td class="fas-cell-thumb">
                            <div class="fas-thumb">
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

                        {{-- Nama + slug --}}
                        <td class="fas-cell-main">
                            <div class="fas-cell-main__body">
                                <a href="{{ route('superadmin.fasilitas.edit', $item) }}"
                                    class="fas-name">{{ $item->jeneng }}</a>
                                @if ($item->tandha)
                                    <code class="fas-slug" title="Slug: {{ $item->tandha }}">
                                        /{{ $item->tandha }}
                                    </code>
                                @endif
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="fas-cell-status">
                            <span class="fas-status fas-status--{{ $item->urip ? 'ok' : 'off' }}">
                                <span class="fas-status__dot" aria-hidden="true"></span>
                                {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>

                        {{-- Urutan --}}
                        <td class="fas-cell-order is-num">
                            <span class="fas-order">#{{ $item->urutan }}</span>
                        </td>

                        {{-- Waktu update --}}
                        <td class="fas-cell-updated">
                            @if ($item->updated_at)
                                <span class="fas-time"
                                    title="{{ $item->updated_at->translatedFormat('d M Y · H:i') }}">
                                    {{ $item->updated_at->diffForHumans(short: true) }}
                                </span>
                            @else
                                <span class="fas-time fas-time--muted">—</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="fas-cell-actions">
                            <div class="fas-actions" role="group" aria-label="Aksi untuk {{ $item->jeneng }}">
                                <a href="{{ route('superadmin.fasilitas.edit', $item) }}" class="fa-btn"
                                    title="Edit fasilitas" aria-label="Edit {{ $item->jeneng }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                    </svg>
                                </a>

                                <form method="POST" action="{{ route('superadmin.fasilitas.toggle', $item) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="fa-btn {{ $item->urip ? 'fa-btn--mute' : 'fa-btn--ok' }}"
                                        title="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }}"
                                        aria-label="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }} {{ $item->jeneng }}">
                                        @if ($item->urip)
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path
                                                    d="M17.94 17.94A10.9 10.9 0 0 1 12 20c-5 0-9.27-3.11-11-8 1-2.9 2.9-5.2 5.4-6.5" />
                                                <path d="M1 1l22 22" />
                                                <path
                                                    d="M9.9 4.24A10.9 10.9 0 0 1 12 4c5 0 9.27 3.11 11 8a11.7 11.7 0 0 1-2.2 3.2" />
                                                <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24" />
                                            </svg>
                                        @else
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        @endif
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('superadmin.fasilitas.destroy', $item) }}"
                                    data-confirm data-confirm-title="Pindahkan ke sampah?"
                                    data-confirm-message="Fasilitas &quot;{{ $item->jeneng }}&quot; akan dipindahkan ke sampah. Anda masih bisa memulihkannya nanti."
                                    data-confirm-ok="Ya, pindahkan" data-confirm-tone="warning">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="fa-btn fa-btn--danger" title="Pindahkan ke sampah"
                                        aria-label="Hapus {{ $item->jeneng }}">
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

@push('css')
    <style>
        .fas-cell-no {
            text-align: center;
            font-variant-numeric: tabular-nums;
            color: var(--mut);
            font-weight: 600;
            font-size: 0.8rem;
        }
    </style>
@endpush
