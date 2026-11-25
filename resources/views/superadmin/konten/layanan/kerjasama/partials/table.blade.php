<div class="kjs-table-wrap">
    <table class="kjs-table">
        <thead>
            <tr>
                <th style="width: 64px;">Urutan</th>
                <th>Instansi / Penawaran</th>
                <th>Jenis &amp; Sektor</th>
                <th>Dokumen &amp; Tautan</th>
                <th>Status</th>
                <th style="width: 104px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td><b>#{{ $item->urutan }}</b></td>
                    <td>
                        <div class="kjs-entity">
                            @if ($item->logo_url)
                                <img src="{{ $item->logo_url }}" alt="{{ $item->jeneng }}" class="kjs-logo">
                            @else
                                <div class="kjs-logo-fallback">
                                    {{ strtoupper(mb_substr($item->jeneng, 0, 2)) }}
                                </div>
                            @endif

                            <div>
                                <div style="font-weight: 700; color: var(--ink);">{{ $item->jeneng }}</div>
                                @if ($item->nomor_dokumen || $item->periode)
                                    <div style="font-size: 11.5px; color: var(--mut); margin-top: 2px;">
                                        {{ implode(' • ', array_filter([$item->nomor_dokumen, $item->periode])) }}
                                    </div>
                                @endif
                                @if ($item->katrangan)
                                    <div style="font-size: 12.5px; color: var(--mut); margin-top: 4px;">
                                        {{ \Illuminate\Support\Str::limit($item->katrangan, 80) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 5px; align-items: flex-start;">
                            <span class="kjs-badge {{ $item->jenis === 'penawaran' ? 'penawaran' : 'mitra' }}">
                                {{ $item->jenis === 'penawaran' ? 'Penawaran' : 'Mitra' }}
                            </span>
                            <span class="kjs-badge">{{ $item->kategori }}</span>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 5px; align-items: flex-start;">
                            @if ($item->file_exists)
                                <a href="{{ asset('storage/' . ltrim($item->path_file, '/')) }}" target="_blank"
                                    rel="noopener" class="kjs-act" title="Lihat dokumen">
                                    {{ $item->file_extension }} &middot; {{ $item->file_size }}
                                </a>
                            @endif

                            @if ($item->tautan)
                                <a href="{{ $item->tautan }}" target="_blank" rel="noopener"
                                    style="font-size: 12px; color: #1f5fbf; text-decoration: none; font-weight: 600;">
                                    Buka Tautan &nearr;
                                </a>
                            @endif

                            @if (!$item->file_exists && !$item->tautan)
                                <span style="font-size: 12px; color: var(--mut);">-</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('superadmin.kerjasama.toggle', $item) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="kjs-badge {{ $item->urip ? 'ok' : 'off' }}"
                                style="cursor: pointer;" title="Klik untuk ubah status publikasi">
                                {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="kjs-acts" style="justify-content: center;">
                            <a href="{{ route('superadmin.kerjasama.edit', $item) }}" class="kjs-act icon"
                                title="Edit data kerjasama" aria-label="Edit {{ $item->jeneng }}">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </a>

                            <button type="button" class="kjs-act icon danger" title="Hapus data kerjasama"
                                aria-label="Hapus {{ $item->jeneng }}" data-confirm-trigger
                                data-confirm-title="Pindahkan ke Tempat Sampah?"
                                data-confirm-message="Data '{{ $item->jeneng }}' akan disembunyikan dari publik dan dipindahkan ke tempat sampah."
                                data-confirm-action="{{ route('superadmin.kerjasama.destroy', $item) }}"
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
