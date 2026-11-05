<div class="tw">
    <table class="prodi-table">
        <thead>
            <tr>
                <th style="width:48px;">#</th>
                <th>Nama prodi</th>
                <th>Fakultas</th>
                <th style="width:80px;">Jenjang</th>
                <th style="width:100px;">Akreditasi</th>
                <th style="width:72px;">Urutan</th>
                <th style="width:100px;">Status</th>
                <th style="width:120px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr @class(['is-muted' => !$item->urip])>
                    <td class="is-muted-text">{{ $items->firstItem() + $loop->index }}</td>
                    <td class="prodi-name">{{ $item->jeneng }}</td>
                    <td>{{ $item->fakultas?->jeneng ?? '—' }}</td>
                    <td><span class="pill">{{ $item->undhak }}</span></td>
                    <td>{{ $item->akred ?: '—' }}</td>
                    <td>{{ $item->urutan }}</td>
                    <td>
                        <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                            {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="row-actions" role="group" aria-label="Aksi {{ $item->jeneng }}">
                            <a href="{{ route('superadmin.prodi.edit', $item) }}" class="ia" title="Edit"
                                aria-label="Edit {{ $item->jeneng }}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('superadmin.prodi.toggle', $item) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="ia {{ $item->urip ? 'ia-mute' : 'ia-ok' }}"
                                    title="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }}"
                                    aria-label="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }} {{ $item->jeneng }}">
                                    @if ($item->urip)
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path
                                                d="M17.94 17.94A10.9 10.9 0 0 1 12 20c-5 0-9.27-3.11-11-8 1-2.9 2.9-5.2 5.4-6.5" />
                                            <path d="M1 1l22 22" />
                                            <path
                                                d="M9.9 4.24A10.9 10.9 0 0 1 12 4c5 0 9.27 3.11 11 8a11.7 11.7 0 0 1-2.2 3.2" />
                                            <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24" />
                                        </svg>
                                    @else
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    @endif
                                </button>
                            </form>

                            {{-- Soft delete nanti: ganti ke data-modal-open --}}
                            <form method="POST" action="{{ route('superadmin.prodi.destroy', $item) }}"
                                onsubmit="return confirm('Hapus program studi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="ia ia-danger" title="Hapus"
                                    aria-label="Hapus {{ $item->jeneng }}" data-modal-open data-modal-type="danger"
                                    data-modal-title="Pindahkan ke sampah?"
                                    data-modal-message='Program studi "{{ $item->jeneng }}" akan dipindahkan ke sampah.'
                                    data-modal-confirm="Ya, pindahkan"
                                    data-modal-action="{{ route('superadmin.prodi.destroy', $item) }}"
                                    data-modal-method="DELETE">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" aria-hidden="true">
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
