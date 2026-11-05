<div class="tw">
    <table>
        <thead>
            <tr>
                <th style="width:48px;">#</th>
                <th>Nama</th>
                <th style="width:90px;">Kode</th>
                <th style="width:110px;">Prodi</th>
                <th style="width:80px;">Urutan</th>
                <th style="width:100px;">Status</th>
                <th style="width:140px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr @class(['is-muted' => !$item->urip])>
                    <td style="color:var(--mut);">{{ $items->firstItem() + $loop->index }}</td>
                    <td style="font-weight:600;white-space:normal;">{{ $item->jeneng }}</td>
                    <td>{{ $item->kodhe ?: '—' }}</td>
                    <td>
                        @if ($item->prodis_count > 0)
                            <span class="badge">{{ $item->prodis_count }}</span>
                        @else
                            <span style="color:var(--mut);">—</span>
                        @endif
                    </td>
                    <td>{{ $item->urutan }}</td>
                    <td>
                        <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                            {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="row-actions" role="group" aria-label="Aksi fakultas">
                            <a href="{{ route('superadmin.fakultas.edit', $item) }}" class="ia" title="Edit">
                                {{-- ikon edit --}}
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('superadmin.fakultas.toggle', $item) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="ia {{ $item->urip ? 'ia-mute' : 'ia-ok' }}"
                                    title="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    {{-- ikon eye --}}
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </form>

                            {{-- HAPUS → modal --}}
                            <button type="button" class="ia ia-danger" title="Hapus" data-modal-open
                                data-modal-type="danger" data-modal-title="Pindahkan ke sampah?"
                                data-modal-message="&quot;{{ e($item->jeneng) }}&quot; akan dipindahkan ke sampah. Bisa dipulihkan kapan saja."
                                data-modal-confirm="Ya, pindahkan"
                                data-modal-action="{{ route('superadmin.fakultas.destroy', $item) }}"
                                data-modal-method="DELETE">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
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
