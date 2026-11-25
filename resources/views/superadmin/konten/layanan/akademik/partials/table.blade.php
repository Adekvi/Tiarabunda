<div class="aka-table-wrap">
    <table class="aka-table">
        <thead>
            <tr>
                <th style="width: 68px;">Urutan</th>
                <th>Dokumen &amp; Keterangan</th>
                <th>Kategori</th>
                <th>Berkas Unduhan</th>
                <th>Status</th>
                <th style="width: 104px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td><b>#{{ $item->urutan }}</b></td>
                    <td>
                        <div style="font-weight: 700; color: var(--ink);">{{ $item->jeneng }}</div>
                        <div style="font-size: 12px; color: var(--mut); margin-top: 2px;">
                            <code>/layanan/akademik/unduh/{{ $item->tandha }}</code>
                        </div>
                        @if ($item->katrangan)
                            <div style="font-size: 12.5px; color: var(--mut); margin-top: 4px;">
                                {{ \Illuminate\Support\Str::limit($item->katrangan, 85) }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="aka-badge">{{ $item->kategori ?: 'Umum' }}</span>
                    </td>
                    <td>
                        @if ($item->file_exists)
                            <a href="{{ route('landing.akademik.download', $item->tandha) }}" target="_blank"
                                rel="noopener" class="aka-act" title="Unduh berkas">
                                {{ $item->file_extension }} &middot; {{ $item->file_size }}
                            </a>
                            <div style="font-size: 11.5px; color: var(--mut); margin-top: 3px;">
                                {{ \Illuminate\Support\Str::limit($item->nama_file_asli, 28) }}
                            </div>
                        @else
                            <span class="aka-badge err">File Tidak Ditemukan</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('superadmin.akademik.toggle', $item) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="aka-badge {{ $item->urip ? 'ok' : 'off' }}"
                                style="cursor: pointer;" title="Klik untuk ubah status publikasi">
                                {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="aka-acts" style="justify-content: center;">
                            <a href="{{ route('superadmin.akademik.edit', $item) }}" class="aka-act icon"
                                title="Edit dokumen" aria-label="Edit {{ $item->jeneng }}">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </a>

                            <button type="button" class="aka-act icon danger" title="Hapus dokumen"
                                aria-label="Hapus {{ $item->jeneng }}" data-confirm-trigger
                                data-confirm-title="Pindahkan ke Tempat Sampah?"
                                data-confirm-message="Dokumen '{{ $item->jeneng }}' akan disembunyikan dari publik dan dipindahkan ke tempat sampah."
                                data-confirm-action="{{ route('superadmin.akademik.destroy', $item) }}"
                                data-confirm-method="DELETE">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="3 6 5 6 21 6" />
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                    <line x1="10" y1="11" x2="10" y2="17" />
                                    <line x1="14" y1="11" x2="14" y2="17" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
