<div class="aka-table-wrap">
    <table class="aka-table">
        <thead>
            <tr>
                <th>Dokumen</th>
                <th>Kategori</th>
                <th>Berkas</th>
                <th>Dihapus Pada</th>
                <th style="width: 230px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>
                        <div style="font-weight: 700; color: var(--ink);">{{ $item->jeneng }}</div>
                        <div style="font-size: 12px; color: var(--mut);">
                            <code>{{ $item->tandha }}</code>
                        </div>
                    </td>
                    <td>
                        <span class="aka-badge">{{ $item->kategori ?: 'Umum' }}</span>
                    </td>
                    <td>
                        <span style="font-size: 12.5px; color: var(--mut);">
                            {{ $item->nama_file_asli ?: ($item->path_file ? basename($item->path_file) : '-') }}
                        </span>
                    </td>
                    <td style="font-size: 12.5px; color: var(--mut);">
                        {{ $item->deleted_at?->format('d M Y, H:i') }}
                    </td>
                    <td>
                        <div class="aka-acts">
                            <form method="POST" action="{{ route('superadmin.akademik.restore', $item->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="aka-act">Pulihkan</button>
                            </form>

                            <button type="button" class="aka-act danger" data-confirm-trigger
                                data-confirm-title="Hapus Permanen Dokumen?"
                                data-confirm-message="Dokumen '{{ $item->jeneng }}' beserta berkas fisiknya akan dihapus permanen dan tidak bisa dikembalikan."
                                data-confirm-action="{{ route('superadmin.akademik.force-delete', $item->id) }}"
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
