<div class="tw prp-table-wrap">
    <table class="prp-table">
        <thead>
            <tr>
                <th class="is-num">#</th>
                <th>Judul & Deskripsi</th>
                <th>Portal</th>
                <th class="is-ord">Urutan</th>
                <th class="is-status">Status</th>
                <th class="is-actions">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                @php $portal = data_get($item->data_tambahan, 'url_portal'); @endphp
                <tr>
                    <td class="is-num">{{ $items->firstItem() + $loop->index }}</td>
                    <td>
                        <div class="prp-info">
                            <span class="prp-info__name">{{ $item->judhul }}</span>
                            @if ($item->isi)
                                <span class="prp-info__desc">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 70) }}
                                </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if ($portal)
                            <a href="{{ $portal }}" target="_blank" rel="noopener noreferrer" class="prp-link">
                                Buka Portal
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    <polyline points="15 3 21 3 21 9"></polyline>
                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                </svg>
                            </a>
                        @else
                            <span style="color:var(--mut);">—</span>
                        @endif
                    </td>
                    <td class="is-ord">{{ $item->urutan }}</td>
                    <td>
                        <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                            {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="is-actions">
                        <div class="row-actions" role="group">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('superadmin.perpus.edit', $item) }}" class="ia" title="Edit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                </svg>
                            </a>

                            {{-- Tombol Toggle --}}
                            <form method="POST" action="{{ route('superadmin.perpus.toggle', $item) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="ia {{ $item->urip ? 'ia-mute' : 'ia-ok' }}"
                                    title="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }}">
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

                            {{-- Tombol Hapus --}}
                            <button type="button" class="ia ia-danger" title="Hapus" data-modal-open
                                data-modal-type="danger" data-modal-title="Pindahkan ke sampah?"
                                data-modal-message='Data "{{ $item->judhul }}" akan dipindahkan ke sampah. Bisa dipulihkan nanti.'
                                data-modal-confirm="Ya, hapus"
                                data-modal-action="{{ route('superadmin.perpus.destroy', $item) }}"
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
