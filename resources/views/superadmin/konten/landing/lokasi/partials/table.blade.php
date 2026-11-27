<div class="lok-table-wrap">
    <table class="lok-table">
        <thead>
            <tr>
                <th style="width: 72px; text-align: center;">Urutan</th>
                <th style="width: 140px;">Foto Gedung</th>
                <th>Kampus &amp; Alamat</th>
                <th style="width: 245px;">Kontak &amp; Jam</th>
                <th style="width: 120px;">Status</th>
                <th style="text-align: right; width: 108px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td style="text-align: center;">
                        <span class="lok-order-pill">#{{ $item->urutan }}</span>
                    </td>
                    <td>
                        <div class="lok-thumb-box">
                            @if ($item->foto)
                                <img src="{{ asset($item->foto) }}" alt="{{ $item->nama }}" class="lok-thumb">
                            @else
                                <div class="lok-thumb-empty">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <rect x="3" y="3" width="18" height="18" rx="2" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" />
                                    </svg>
                                    <span>SVG Default</span>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="lok-meta-tags">
                            <span class="lok-badge lok-badge--blue">Kampus {{ $item->kode }}</span>
                            <span class="lok-badge lok-badge--gold">{{ $item->badge }}</span>
                        </div>
                        <div class="lok-item-title">{{ $item->nama }}</div>
                        <div class="lok-item-addr">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 21s-7-5.686-7-11a7 7 0 1 1 14 0c0 5.314-7 11-7 11Z" />
                                <circle cx="12" cy="10" r="2.5" />
                            </svg>
                            <span>{{ \Illuminate\Support\Str::limit($item->alamat, 78) }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="lok-contact-stack">
                            <div class="lok-contact-row">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z" />
                                </svg>
                                <strong>{{ $item->telepon }}</strong>
                            </div>
                            <div class="lok-contact-row lok-contact-row--muted">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="M12 7v5l3 2" />
                                </svg>
                                <span>{{ $item->jam }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <form action="{{ route('superadmin.lokasi.toggle', $item) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="lok-status-pill {{ $item->urip ? 'is-active' : 'is-inactive' }}"
                                title="Klik untuk mengubah status tampil">
                                <span class="lok-status-dot" aria-hidden="true"></span>
                                <span>{{ $item->urip ? 'Aktif' : 'Nonaktif' }}</span>
                            </button>
                        </form>
                    </td>
                    <td style="text-align: right;">
                        <div class="lok-actions">
                            <a href="{{ route('superadmin.lokasi.edit', $item) }}"
                                class="lok-icon-btn lok-icon-btn--edit" data-tip="Edit Lokasi"
                                aria-label="Edit {{ $item->nama }}">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.1" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                                </svg>
                            </a>

                            <button type="button" class="lok-icon-btn lok-icon-btn--delete" data-tip="Hapus"
                                aria-label="Hapus {{ $item->nama }}" data-confir-open
                                data-action="{{ route('superadmin.lokasi.destroy', $item) }}" data-method="DELETE"
                                data-title="Pindahkan ke Tong Sampah?"
                                data-message="Data {{ $item->nama }} akan disembunyikan dari beranda dan dipindahkan ke tong sampah."
                                data-btn-class="lok-btn lok-btn--danger" data-btn-text="Ya, Pindahkan">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.1" stroke-linecap="round"
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
