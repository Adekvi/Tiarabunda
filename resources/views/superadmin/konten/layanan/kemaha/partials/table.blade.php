<div class="tw">
    <table>
        <thead>
            <tr>
                <th style="width:48px;">#</th>
                <th>Judul</th>
                <th>Kontak</th>
                <th style="width:80px;">Urutan</th>
                <th style="width:100px;">Status</th>
                <th style="width:128px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                @php
                    $kontak = data_get($item->data_tambahan, 'kontak');
                    $email = data_get($item->data_tambahan, 'email');
                @endphp
                <tr>
                    <td style="color:var(--mut);">
                        {{ $items->firstItem() + $loop->index }}
                    </td>
                    <td style="font-weight:600;white-space:normal;max-width:260px;">
                        {{ $item->judhul }}
                        @if ($item->isi)
                            <div style="font-weight:400;color:var(--mut);font-size:0.82rem;margin-top:2px;">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 70) }}
                            </div>
                        @endif
                    </td>
                    <td style="white-space:normal;font-size:0.85rem;color:var(--mut);">
                        {{ $kontak ?: ($email ?: '—') }}
                    </td>
                    <td>{{ $item->urutan }}</td>
                    <td>
                        <span class="badge {{ $item->urip ? 'ok' : 'err' }}">
                            {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="row-actions" role="group">
                            <a href="{{ route('superadmin.kemaha.edit', $item) }}" class="ia" title="Edit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('superadmin.kemaha.toggle', $item) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="ia {{ $item->urip ? 'ia-mute' : 'ia-ok' }}"
                                    title="{{ $item->urip ? 'Nonaktifkan' : 'Aktifkan' }}">
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

                            <button type="button" class="ia ia-danger" title="Hapus" data-confirm-delete
                                data-url="{{ route('superadmin.kemaha.destroy', $item) }}"
                                data-name="{{ $item->judhul }}">
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
