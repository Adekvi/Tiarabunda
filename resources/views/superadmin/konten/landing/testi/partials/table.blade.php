<div class="tst-table-wrap">
    <table class="tst-table">
        <thead>
            <tr>
                <th style="width: 66px;">Urut</th>
                <th>Pemberi Testimoni</th>
                <th>Sorotan &amp; Kutipan</th>
                <th style="width: 112px;">Rating</th>
                <th style="width: 120px;">Status</th>
                <th style="width: 175px; text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $idx => $item)
                @php
                    $displayName = $item->nama ?: 'Tanpa Nama';
                    $isSpotlight = !$isTrash && $item->urip && $items->currentPage() === 1 && $idx === 0;
                @endphp
                <tr class="{{ $item->urip ? '' : 'is-inactive' }}">
                    <td>
                        <div class="tst-order-col">
                            <span class="tst-order-pill">#{{ $item->urutan }}</span>
                            @if ($isSpotlight)
                                <span class="tst-spot-tag" title="Tampil sebagai sorotan utama">Utama</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="tst-person">
                            <div class="tst-avatar-wrap">
                                @if ($item->foto_url !== '')
                                    <img src="{{ $item->foto_url }}" alt="{{ $displayName }}" class="tst-avatar">
                                @else
                                    <span class="tst-avatar tst-avatar--init">{{ $item->inisial }}</span>
                                @endif
                                @if ($item->urip)
                                    <span class="tst-avatar-check" title="Terverifikasi & Tayang">✓</span>
                                @endif
                            </div>

                            <div class="tst-person-meta">
                                <div class="tst-person-head">
                                    <strong>{{ $displayName }}</strong>
                                    <span class="tst-cat-badge tst-cat-badge--{{ $item->kategori_key }}">
                                        {{ $item->kategori_label }}
                                    </span>
                                </div>
                                @if ($item->peran)
                                    <span class="tst-person-role">{{ $item->peran }}</span>
                                @endif
                                @if ($item->angkatan || $item->instansi)
                                    <small class="tst-person-sub">
                                        {{ collect([$item->angkatan, $item->instansi])->filter()->implode(' • ') }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="tst-quote-cell">
                            @if ($item->sorotan)
                                <strong>“{{ $item->sorotan }}”</strong>
                            @endif
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) $item->kutipan), 125) }}</p>
                            <span class="tst-updated-at">
                                Diperbarui {{ $item->updated_at?->diffForHumans() }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="tst-rating-box">
                            <span class="tst-stars" title="{{ $item->rating }} / 5">
                                {{ str_repeat('★', $item->rating) }}{{ str_repeat('☆', 5 - $item->rating) }}
                            </span>
                            <small>{{ number_format($item->rating, 1) }} / 5.0</small>
                        </div>
                    </td>
                    <td>
                        <form action="{{ route('superadmin.testimoni.toggle', $item) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="tst-status-btn {{ $item->urip ? 'is-on' : 'is-off' }}"
                                title="Klik untuk ubah status tayang">
                                <span class="tst-dot"></span>
                                {{ $item->urip ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div class="tst-row-actions">
                            <button type="button" class="lok-btn lok-btn--ghost tst-btn-sm tst-btn-ico"
                                title="Pratinjau Tampilan Kartu" data-preview-open data-nama="{{ $displayName }}"
                                data-peran="{{ $item->peran ?: 'Civitas Akademika' }}"
                                data-angkatan="{{ $item->angkatan ?: 'Terverifikasi' }}"
                                data-kategori="{{ $item->kategori_label }}"
                                data-instansi="{{ $item->instansi ?? '' }}"
                                data-sorotan="{{ $item->sorotan ?: 'Pengalaman Akademik & Praktik' }}"
                                data-kutipan="{{ strip_tags((string) $item->kutipan) }}"
                                data-rating="{{ $item->rating }}" data-foto="{{ $item->foto_url }}"
                                data-inisial="{{ $item->inisial }}"
                                data-edit-url="{{ route('superadmin.testimoni.edit', $item) }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.1">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>

                            <a href="{{ route('superadmin.testimoni.edit', $item) }}"
                                class="lok-btn lok-btn--ghost tst-btn-sm">
                                Edit
                            </a>

                            <button type="button" class="lok-btn lok-btn--danger tst-btn-sm" data-confirm-open
                                data-confirm-title="Hapus Testimoni?"
                                data-confirm-message="Testimoni dari {{ $displayName }} akan dipindahkan ke tong sampah dan disembunyikan dari beranda."
                                data-confirm-action="{{ route('superadmin.testimoni.destroy', $item) }}"
                                data-confirm-method="DELETE" data-confirm-btn="Pindahkan ke Sampah"
                                data-confirm-variant="danger">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
