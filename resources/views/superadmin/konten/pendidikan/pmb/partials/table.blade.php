<div class="tw pmb-table-wrap">
    <table class="pmb-table">
        <thead>
            <tr>
                <th class="is-num">#</th>
                <th>Nama Jalur</th>
                <th class="is-slug">Tanda</th>
                <th class="is-date">Dibuka</th>
                <th class="is-date">Ditutup</th>
                <th class="is-ord">Urutan</th>
                <th class="is-status">Status</th>
                <th class="is-actions">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                @php
                    $now = now();
                    $isOpen =
                        $item->urip &&
                        $item->wektu_bukak &&
                        $item->wektu_tutup &&
                        $now->between($item->wektu_bukak, $item->wektu_tutup);
                    $isSoon =
                        $item->urip &&
                        $item->wektu_tutup &&
                        $item->wektu_tutup->isFuture() &&
                        $now->diffInDays($item->wektu_tutup, false) <= 14;
                @endphp
                <tr>
                    <td class="is-num">{{ $items->firstItem() + $loop->index }}</td>

                    <td>
                        <div class="pmb-name">
                            <span class="pmb-name__main">{{ $item->jeneng }}</span>
                            @if ($isOpen)
                                <span class="pmb-tag pmb-tag--open">Dibuka</span>
                            @elseif ($isSoon)
                                <span class="pmb-tag pmb-tag--soon">Segera tutup</span>
                            @endif
                        </div>
                    </td>

                    <td class="is-slug">
                        @if ($item->tandha)
                            <code class="pmb-code">{{ $item->tandha }}</code>
                        @else
                            <span class="is-muted-text">—</span>
                        @endif
                    </td>

                    <td class="is-date">
                        @if ($item->wektu_bukak)
                            <time datetime="{{ $item->wektu_bukak->toDateString() }}">
                                {{ $item->wektu_bukak->format('d M Y') }}
                            </time>
                        @else
                            <span class="is-muted-text">—</span>
                        @endif
                    </td>

                    <td class="is-date">
                        @if ($item->wektu_tutup)
                            <time datetime="{{ $item->wektu_tutup->toDateString() }}"
                                class="{{ $isSoon ? 'pmb-time--soon' : '' }}">
                                {{ $item->wektu_tutup->format('d M Y') }}
                            </time>
                        @else
                            <span class="is-muted-text">—</span>
                        @endif
                    </td>

                    <td class="is-ord">{{ $item->urutan }}</td>

                    <td class="is-status">
                        <span class="pmb-badge {{ $item->urip ? 'is-on' : 'is-off' }}">
                            <i aria-hidden="true"></i>
                            {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>

                    <td class="is-actions">
                        <div class="pmb-actions" role="group" aria-label="Aksi untuk {{ $item->jeneng }}">
                            <a href="{{ route('superadmin.pmb.edit', $item) }}" class="pmb-act" title="Edit"
                                aria-label="Edit {{ $item->jeneng }}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('superadmin.pmb.toggle', $item) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="pmb-act {{ $item->urip ? 'is-mute' : 'is-ok' }}"
                                    title="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }}"
                                    aria-label="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    @if ($item->urip)
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
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
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    @endif
                                </button>
                            </form>

                            <button type="button" class="pmb-act is-danger" title="Hapus"
                                aria-label="Hapus {{ $item->jeneng }}" data-modal-open
                                data-modal-action="{{ route('superadmin.pmb.destroy', $item) }}"
                                data-modal-method="DELETE" data-modal-type="danger" data-modal-title="Hapus jalur PMB?"
                                data-modal-message="Jalur &laquo;{{ $item->jeneng }}&raquo; akan dihapus permanen. Tindakan ini tidak bisa dibatalkan."
                                data-modal-confirm="Ya, hapus">
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
