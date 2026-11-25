<div class="kjs-table-wrap">
    <table class="kjs-table">
        <thead>
            <tr>
                <th>Instansi / Penawaran</th>
                <th>Jenis</th>
                <th>Sektor</th>
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
                        <span class="kjs-badge {{ $item->jenis === 'penawaran' ? 'penawaran' : 'mitra' }}">
                            {{ $item->jenis === 'penawaran' ? 'Penawaran' : 'Mitra' }}
                        </span>
                    </td>
                    <td><span class="kjs-badge">{{ $item->kategori }}</span></td>
                    <td style="font-size: 12.5px; color: var(--mut);">
                        {{ $item->deleted_at?->format('d M Y, H:i') }}
                    </td>
                    <td>
                        <div class="kjs-acts">
                            <form method="POST" action="{{ route('superadmin.kerjasama.restore', $item->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="kjs-act">Pulihkan</button>
                            </form>

                            <button type="button" class="kjs-act danger" data-confirm-trigger
                                data-confirm-title="Hapus Permanen Data Kerjasama?"
                                data-confirm-message="Data '{{ $item->jeneng }}' beserta logo dan berkasnya akan dihapus permanen."
                                data-confirm-action="{{ route('superadmin.kerjasama.force-delete', $item->id) }}"
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
