<div class="tst-table-wrap">
    <table class="tst-table">
        <thead>
            <tr>
                <th>Pemberi Testimoni</th>
                <th style="width: 150px;">Kategori</th>
                <th>Sorotan &amp; Kutipan</th>
                <th style="width: 145px;">Dihapus Pada</th>
                <th style="width: 195px; text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                @php
                    $displayName = $item->nama ?: 'Tanpa Nama';
                @endphp
                <tr>
                    <td>
                        <div class="tst-person">
                            @if ($item->foto_url !== '')
                                <img src="{{ $item->foto_url }}" alt="{{ $displayName }}" class="tst-avatar">
                            @else
                                <span class="tst-avatar tst-avatar--init">{{ $item->inisial }}</span>
                            @endif
                            <div class="tst-person-meta">
                                <strong>{{ $displayName }}</strong>
                                @if ($item->peran)
                                    <span>{{ $item->peran }}</span>
                                @endif
                                @if ($item->angkatan || $item->instansi)
                                    <small>
                                        {{ collect([$item->angkatan, $item->instansi])->filter()->implode(' • ') }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="tst-cat-badge tst-cat-badge--{{ $item->kategori_key }}">
                            {{ $item->kategori_label }}
                        </span>
                    </td>
                    <td>
                        <div class="tst-quote-cell">
                            @if ($item->sorotan)
                                <strong>{{ $item->sorotan }}</strong>
                            @endif
                            <p>“{{ \Illuminate\Support\Str::limit(strip_tags((string) $item->kutipan), 110) }}”</p>
                        </div>
                    </td>
                    <td>
                        <span class="tst-muted-date">
                            {{ $item->deleted_at?->format('d M Y, H:i') }}
                        </span>
                    </td>
                    <td>
                        <div class="tst-row-actions">
                            <button type="button" class="lok-btn lok-btn--ghost tst-btn-sm" data-confirm-open
                                data-confirm-title="Pulihkan Testimoni?"
                                data-confirm-message="Testimoni dari {{ $displayName }} akan dikembalikan ke daftar aktif."
                                data-confirm-action="{{ route('superadmin.testimoni.restore', $item->id) }}"
                                data-confirm-method="PATCH" data-confirm-btn="Pulihkan" data-confirm-variant="primary">
                                Pulihkan
                            </button>

                            <button type="button" class="lok-btn lok-btn--danger tst-btn-sm" data-confirm-open
                                data-confirm-title="Hapus Permanen?"
                                data-confirm-message="Data dan foto testimoni dari {{ $displayName }} akan dihapus permanen dan tidak dapat dikembalikan."
                                data-confirm-action="{{ route('superadmin.testimoni.force-delete', $item->id) }}"
                                data-confirm-method="DELETE" data-confirm-btn="Hapus Permanen"
                                data-confirm-variant="danger">
                                Permanen
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
