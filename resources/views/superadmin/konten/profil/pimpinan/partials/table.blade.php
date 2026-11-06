<div class="tw pim-table-wrap">
    <table class="pim-table">
        <thead>
            <tr>
                <th class="is-num">#</th>
                <th>Nama Lengkap</th>
                <th>Jabatan</th>
                <th class="is-ord">Urutan</th>
                <th class="is-status">Status</th>
                <th class="is-actions">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td class="is-num">{{ $items->firstItem() + $loop->index }}</td>

                    {{-- Kolom Nama & Foto --}}
                    <td>
                        <div class="pim-profile">
                            <div class="pim-avatar">
                                @if ($item->path_foto)
                                    <img src="{{ asset('storage/' . $item->path_foto) }}" alt="{{ $item->jeneng }}">
                                @else
                                    <span class="pim-avatar__initial">
                                        {{ strtoupper(substr($item->jeneng, 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <span class="pim-info__name">{{ $item->jeneng }}</span>
                        </div>
                    </td>

                    {{-- Kolom Jabatan --}}
                    <td>
                        <span class="pim-info__role">{{ $item->kalungguhan }}</span>
                    </td>

                    {{-- Kolom Urutan --}}
                    <td class="is-ord">{{ $item->urutan }}</td>

                    {{-- Kolom Status --}}
                    <td class="is-status">
                        <span class="pim-badge {{ $item->urip ? 'is-on' : 'is-off' }}">
                            <i aria-hidden="true"></i>
                            {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>

                    {{-- Kolom Aksi --}}
                    <td class="is-actions">
                        <div class="pim-actions" role="group" aria-label="Aksi untuk {{ $item->jeneng }}">
                            <a href="{{ route('superadmin.pimpinan.edit', $item) }}" class="pim-act" title="Edit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('superadmin.pimpinan.toggle', $item) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="pim-act {{ $item->urip ? 'is-mute' : 'is-ok' }}"
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

                            <button type="button" class="pim-act is-danger" title="Hapus" data-modal-open
                                data-modal-action="{{ route('superadmin.pimpinan.destroy', $item) }}"
                                data-modal-method="DELETE" data-modal-type="danger" data-modal-title="Hapus Pimpinan?"
                                data-modal-message='Data "{{ $item->jeneng }}" akan dipindahkan ke sampah.'
                                data-modal-confirm="Ya, hapus">
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
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
