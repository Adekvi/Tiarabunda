<div class="lppm-table-wrap">
    <table class="lppm-table">
        <thead>
            <tr>
                <th>Judul Konten</th>
                <th>Kategori</th>
                <th>Dihapus Pada</th>
                <th style="width: 230px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>
                        <div style="font-weight: 700; color: var(--ink);">{{ $item->judhul }}</div>
                        <div style="font-size: 12px; color: var(--mut);">
                            <code>{{ $item->tandha }}</code>
                        </div>
                    </td>
                    <td>
                        <span class="lppm-badge {{ $item->kategori }}">{{ $item->kategori_label }}</span>
                    </td>
                    <td style="font-size: 12.5px; color: var(--mut);">
                        {{ $item->deleted_at?->format('d M Y, H:i') }}
                    </td>
                    <td>
                        <div class="lppm-acts">
                            <form method="POST" action="{{ route('superadmin.lppm.restore', $item->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="lppm-act">Pulihkan</button>
                            </form>

                            <button type="button" class="lppm-act danger" data-confirm-trigger
                                data-confirm-title="Hapus Permanen Konten LPPM?"
                                data-confirm-message="Konten '{{ $item->judhul }}' akan dihapus permanen dari database."
                                data-confirm-action="{{ route('superadmin.lppm.force-delete', $item->id) }}"
                                data-confirm-method="DELETE">
                                Hapus Permanen
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
