<div class="tw vis-table-wrap">
    <table class="vis-table">
        <thead>
            <tr>
                <th class="is-num">#</th>
                <th class="is-bagean">Bagian</th>
                <th class="is-konten">Judul & Ringkasan</th>
                <th class="is-ord">Urutan</th>
                <th class="is-status">Status</th>
                <th class="is-actions">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                @php
                    $isVisi = ($item->bagean ?? '') === 'visi';
                @endphp
                <tr>
                    <td class="is-num">{{ $items->firstItem() + $loop->index }}</td>

                    <td class="is-bagean">
                        <span class="vis-badge {{ $isVisi ? 'is-on' : 'is-soon' }}">
                            <i aria-hidden="true"></i>
                            {{ $isVisi ? 'Visi' : 'Misi' }}
                        </span>
                    </td>

                    <td class="is-konten">
                        <div class="vis-title">{{ $item->judhul ?? 'Tanpa judul' }}</div>
                        <div class="vis-desc">
                            @if (!$isVisi && is_array($item->dhaptar) && count($item->dhaptar))
                                <span class="vis-tag vis-tag--warn">{{ count($item->dhaptar) }} poin misi</span>
                            @else
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->isi ?? ''), 100) ?: 'Belum ada narasi.' }}
                            @endif
                        </div>
                    </td>

                    <td class="is-ord">{{ $item->urutan }}</td>

                    <td class="is-status">
                        <span class="vis-badge {{ $item->urip ? 'is-on' : 'is-off' }}">
                            <i aria-hidden="true"></i>
                            {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>

                    <td class="is-actions">
                        {{-- Tambahkan div wrapper ini --}}
                        <div class="vis-actions-wrap">
                            <div class="vis-actions" role="group" aria-label="Aksi untuk {{ $item->judhul }}">
                                {{-- Edit --}}
                                <a href="{{ route('superadmin.visimisi.edit', $item) }}" class="vis-act" title="Edit"
                                    aria-label="Edit {{ $item->judhul }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                    </svg>
                                </a>

                                {{-- Toggle --}}
                                <form method="POST" action="{{ route('superadmin.visimisi.toggle', $item) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="vis-act {{ $item->urip ? 'is-mute' : 'is-ok' }}"
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

                                {{-- Hapus (Soft Delete) --}}
                                <button type="button" class="vis-act is-danger" title="Hapus"
                                    aria-label="Hapus {{ $item->judhul }}" data-modal-open
                                    data-modal-action="{{ route('superadmin.visimisi.destroy', $item) }}"
                                    data-modal-method="DELETE" data-modal-type="danger"
                                    data-modal-title="Hapus data {{ $isVisi ? 'visi' : 'misi' }}?"
                                    data-modal-message="Data &laquo;{{ $item->judhul }}&raquo; akan dipindahkan ke sampah. Anda masih bisa memulihkannya nanti."
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
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
