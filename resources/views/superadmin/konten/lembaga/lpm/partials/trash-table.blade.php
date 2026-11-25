<div class="lpm-table-wrap">
    <table class="lpm-table">
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
                        <span class="lpm-badge {{ $item->kategori }}">{{ $item->kategori_label }}</span>
                    </td>
                    <td style="font-size: 12.5px; color: var(--mut);">
                        {{ $item->deleted_at?->format('d M Y, H:i') }}
                    </td>
                    <td>
                        <div class="lpm-acts">
                            <form method="POST" action="{{ route('superadmin.lpm.restore', $item->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="lpm-act">Pulihkan</button>
                            </form>

                            <button type="button" class="lpm-act danger" data-confirm-trigger
                                data-confirm-title="Hapus Permanen Konten LPM?"
                                data-confirm-message="Konten '{{ $item->judhul }}' beserta berkasnya akan dihapus permanen."
                                data-confirm-action="{{ route('superadmin.lpm.force-delete', $item->id) }}"
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
