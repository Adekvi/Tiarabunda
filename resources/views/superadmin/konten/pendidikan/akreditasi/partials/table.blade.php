<div class="tw akr-table-wrap">
    <table class="akr-table">
        <thead>
            <tr>
                <th class="is-num">#</th>
                <th>Judul Akreditasi</th>
                <th>Jenis</th>
                <th>Program Studi</th>
                <th>Status Akred.</th>
                <th class="is-status">Publikasi</th>
                <th class="is-actions">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td class="is-num">{{ $items->firstItem() + $loop->index }}</td>

                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            {{-- Bagian Gambar --}}
                            <div class="akr-img" style="flex-shrink: 0;">
                                @if ($item->gambar_url)
                                    <img src="{{ $item->gambar_url }}" alt="{{ $item->jeneng }}" loading="lazy"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                    <span class="akr-img__placeholder" style="display:none;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M4 4h16v16H4z" />
                                            <path d="M4 12h16" />
                                            <path d="M12 4v16" />
                                        </svg>
                                    </span>
                                @else
                                    <span class="akr-img__placeholder">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M4 4h16v16H4z" />
                                            <path d="M4 12h16" />
                                            <path d="M12 4v16" />
                                        </svg>
                                    </span>
                                @endif
                            </div>

                            {{-- Bagian Judul --}}
                            <div class="akr-title" style="line-height: 1.4;">
                                <div style="font-weight: 700; color: var(--ink); font-size: 0.9rem;">
                                    {{ $item->jeneng }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <span class="akr-badge {{ $item->bagean === 'institusi' ? 'is-inst' : 'is-prodi' }}">
                            {{ $item->bagean === 'institusi' ? 'Institusi' : 'Prodi' }}
                        </span>
                    </td>

                    <td>
                        <span class="akr-prodi-name">{{ $item->prodi?->jeneng ?? '—' }}</span>
                    </td>

                    <td>
                        <span class="akr-status">{{ $item->status_akred ?: '—' }}</span>
                    </td>

                    <td class="is-status">
                        <span class="akr-badge {{ $item->urip ? 'is-on' : 'is-off' }}">
                            <i aria-hidden="true"></i>
                            {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>

                    <td class="is-actions">
                        <div class="akr-actions" role="group" aria-label="Aksi untuk {{ $item->jeneng }}">
                            <a href="{{ route('superadmin.akreditasi.edit', $item) }}" class="akr-act" title="Edit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('superadmin.akreditasi.toggle', $item) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="akr-act {{ $item->urip ? 'is-mute' : 'is-ok' }}"
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

                            <button type="button" class="akr-act is-danger" title="Hapus" data-modal-open
                                data-modal-action="{{ route('superadmin.akreditasi.destroy', $item) }}"
                                data-modal-method="DELETE" data-modal-type="danger" data-modal-title="Hapus Akreditasi?"
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
