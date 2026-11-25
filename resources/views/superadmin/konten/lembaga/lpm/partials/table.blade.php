<div class="lpm-table-wrap">
    <table class="lpm-table">
        <thead>
            <tr>
                <th style="width: 64px;">Urutan</th>
                <th>Judul &amp; Ringkasan</th>
                <th>Kategori</th>
                <th>Info Mutu / Berkas</th>
                <th>Status</th>
                <th style="width: 104px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td><b>#{{ $item->urutan }}</b></td>
                    <td>
                        <div style="font-weight: 700; color: var(--ink);">{{ $item->judhul }}</div>
                        @if ($item->ringkasan)
                            <div style="font-size: 12.5px; color: var(--mut); margin-top: 3px;">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->ringkasan), 95) }}
                            </div>
                        @endif
                        @if ($item->meta('kode_dokumen') || $item->meta('lembaga_akreditasi') || $item->meta('peringkat'))
                            <div class="lpm-meta-tags">
                                @if ($item->meta('kode_dokumen'))
                                    <span class="lpm-meta-chip">{{ $item->meta('kode_dokumen') }}</span>
                                @endif
                                @if ($item->meta('lembaga_akreditasi'))
                                    <span class="lpm-meta-chip">{{ $item->meta('lembaga_akreditasi') }}</span>
                                @endif
                                @if ($item->meta('peringkat'))
                                    <span class="lpm-meta-chip" style="color: #15803d; font-weight: 600;">
                                        {{ $item->meta('peringkat') }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="lpm-badge {{ $item->kategori }}">
                            {{ $item->kategori_label }}
                        </span>
                    </td>
                    <td>
                        <div
                            style="display: flex; flex-direction: column; gap: 5px; align-items: flex-start; font-size: 12px;">
                            @if ($item->meta('siklus_ppepp') || $item->meta('masa_berlaku') || $item->meta('tahun'))
                                <span style="color: var(--ink); font-weight: 600;">
                                    {{ implode(' • ', array_filter([$item->meta('siklus_ppepp'), $item->meta('masa_berlaku') ?: $item->meta('tahun')])) }}
                                </span>
                            @endif

                            @if ($item->file_exists)
                                <a href="{{ asset('storage/' . ltrim($item->path_file, '/')) }}" target="_blank"
                                    rel="noopener" class="lpm-act" title="Unduh dokumen mutu">
                                    {{ $item->file_extension }} &middot; {{ $item->file_size }}
                                </a>
                            @endif

                            @if ($item->meta('tautan'))
                                <a href="{{ $item->meta('tautan') }}" target="_blank" rel="noopener"
                                    style="color: #1f5fbf; text-decoration: none; font-weight: 600;">
                                    {{ $item->meta('label_tautan', 'Buka Tautan') }} &nearr;
                                </a>
                            @endif

                            @if (
                                !$item->file_exists &&
                                    !$item->meta('tautan') &&
                                    !$item->meta('siklus_ppepp') &&
                                    !$item->meta('masa_berlaku') &&
                                    !$item->meta('tahun'))
                                <span style="color: var(--mut);">-</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('superadmin.lpm.toggle', $item) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="lpm-badge {{ $item->urip ? 'ok' : 'off' }}"
                                style="cursor: pointer;" title="Klik untuk ubah status publikasi">
                                {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="lpm-acts" style="justify-content: center;">
                            <a href="{{ route('superadmin.lpm.edit', $item) }}" class="lpm-act icon"
                                title="Edit konten LPM" aria-label="Edit {{ $item->judhul }}">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </a>

                            <button type="button" class="lpm-act icon danger" title="Hapus konten LPM"
                                aria-label="Hapus {{ $item->judhul }}" data-confirm-trigger
                                data-confirm-title="Pindahkan ke Tempat Sampah?"
                                data-confirm-message="Konten '{{ $item->judhul }}' akan disembunyikan dari publik dan dipindahkan ke tempat sampah."
                                data-confirm-action="{{ route('superadmin.lpm.destroy', $item) }}"
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
