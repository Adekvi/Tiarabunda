<div class="info-table-wrap">
    <table class="info-table">
        <thead>
            <tr>
                <th class="is-num">#</th>
                <th class="is-poster">Poster</th>
                <th class="is-title">Judul & Kategori</th>
                <th class="is-time">Tanggal</th>
                <th class="is-order">Urutan</th>
                <th class="is-status">Status</th>
                <th class="is-actions">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td class="is-num">{{ $items->firstItem() + $loop->index }}</td>

                    <td class="is-poster">
                        <div class="info-poster-box">
                            @if ($item->img)
                                <img src="{{ asset('storage/' . $item->img) }}" alt="Poster">
                            @else
                                <span class="info-poster-empty">No img</span>
                            @endif
                        </div>
                    </td>

                    <td class="is-title">
                        <div class="info-title-wrap">
                            <span class="info-title-text">{{ $item->t }}</span>
                            <div class="info-title-meta">
                                <span class="badge-kat">{{ $item->k ?: 'Tanpa Kategori' }}</span>
                                <span class="info-desc-text">{{ \Illuminate\Support\Str::limit($item->d, 60) }}</span>
                            </div>
                        </div>
                    </td>

                    <td class="is-time">{{ $item->tgl }}</td>

                    <td class="is-order">
                        <span class="badge-order">{{ $item->urutan }}</span>
                    </td>

                    <td class="is-status">
                        <form action="{{ route('superadmin.info.toggle', $item) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="badge {{ $item->urip ? 'ok' : 'err' }}">
                                {{ $item->urip ? 'Aktif' : 'Draf' }}
                            </button>
                        </form>
                    </td>

                    <td class="is-actions">
                        <div class="info-actions">
                            <a href="{{ route('superadmin.info.edit', $item) }}" class="info-act" title="Edit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                </svg>
                            </a>
                            <button type="button" class="info-act is-danger" title="Hapus" data-modal-open
                                data-modal-type="danger" data-modal-title="Pindahkan ke sampah?"
                                data-modal-message='Info "{{ $item->t }}" akan dipindahkan ke sampah.'
                                data-modal-confirm="Ya, hapus"
                                data-modal-action="{{ route('superadmin.info.destroy', $item) }}"
                                data-modal-method="DELETE">
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
