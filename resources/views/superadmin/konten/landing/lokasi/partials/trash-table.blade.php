<div class="lok-table-wrap">
    <table class="lok-table">
        <thead>
            <tr>
                <th style="width: 130px;">Foto Gedung</th>
                <th>Kampus &amp; Alamat</th>
                <th style="width: 165px;">Dihapus Pada</th>
                <th style="text-align: right; width: 215px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>
                        @if ($item->foto)
                            <img src="{{ asset($item->foto) }}" alt="{{ $item->nama }}" class="lok-thumb"
                                style="opacity: .75;">
                        @else
                            <div class="lok-thumb-empty">SVG Default</div>
                        @endif
                    </td>
                    <td>
                        <span class="lok-badge lok-badge--muted" style="margin-bottom: 4px;">Kampus
                            {{ $item->kode }}</span>
                        <div style="font-weight: 700; margin-bottom: 2px;">{{ $item->nama }}</div>
                        <div style="font-size: 12px; color: var(--mut);">
                            {{ \Illuminate\Support\Str::limit($item->alamat, 80) }}
                        </div>
                    </td>
                    <td style="font-size: 12px; color: var(--mut);">
                        {{ $item->deleted_at?->format('d M Y, H:i') }} WIB
                    </td>
                    <td style="text-align: right;">
                        <div class="lok-actions">
                            <button type="button" class="lok-btn lok-btn--sm lok-btn--success" data-confir-open
                                data-action="{{ route('superadmin.lokasi.restore', $item->id) }}" data-method="PATCH"
                                data-title="Pulihkan Lokasi Kampus?"
                                data-message="Data {{ $item->nama }} akan dikembalikan ke daftar lokasi aktif."
                                data-btn-class="lok-btn lok-btn--success" data-btn-text="Ya, Pulihkan">
                                Pulihkan
                            </button>
                            <button type="button" class="lok-btn lok-btn--sm lok-btn--danger" data-confir-open
                                data-action="{{ route('superadmin.lokasi.force-delete', $item->id) }}"
                                data-method="DELETE" data-title="Hapus Permanen?"
                                data-message="Data {{ $item->nama }} beserta file fotonya akan dihapus selamanya dan tidak dapat dipulihkan."
                                data-btn-class="lok-btn lok-btn--danger" data-btn-text="Hapus Permanen">
                                Hapus Permanen
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
