<div class="card">
    <div class="fas-header">
        <div class="fas-header__text">
            <h2 style="margin-bottom:4px;">
                {{ $trashed ? 'Sampah Fasilitas' : 'Kelola Fasilitas' }}
            </h2>
            <p style="margin:0;font-size:0.85rem;color:var(--mut);">
                @if ($trashed)
                    Data fasilitas yang telah dihapus. Pulihkan atau hapus permanen.
                @else
                    Galeri fasilitas kampus. Gambar dioptimasi saat unggah agar halaman tetap cepat.
                @endif
            </p>
        </div>

        <div class="fas-header__actions">
            @if ($trashed)
                <a href="{{ route('superadmin.fasilitas.index') }}" class="btn gh">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round">
                        <path d="M19 12H5" />
                        <path d="m12 19-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            @else
                <a href="{{ route('superadmin.fasilitas.trashed') }}" class="btn gh">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round">
                        <path d="M3 6h18" />
                        <path d="M8 6V4h8v2" />
                        <path d="M19 6l-1 14H6L5 6" />
                    </svg>
                    Sampah
                </a>
                <a href="{{ route('superadmin.fasilitas.create') }}" class="btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.2" stroke-linecap="round">
                        <path d="M12 5v14" />
                        <path d="M5 12h14" />
                    </svg>
                    Tambah
                </a>
            @endif
        </div>
    </div>

    @unless ($trashed)
        <form method="GET" action="{{ route('superadmin.fasilitas.index') }}" class="faq-filter">
            <div class="f" style="margin-bottom:0;">
                <label for="q">Cari</label>
                <div class="field-icon">
                    <svg class="field-icon__svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <circle cx="11" cy="11" r="7" />
                        <path d="M20 20l-3.5-3.5" />
                    </svg>
                    <input id="q" type="search" name="q" value="{{ request('q') }}"
                        placeholder="Nama fasilitas…" autocomplete="off">
                </div>
            </div>
            <div class="f" style="margin-bottom:0;min-width:150px;">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="">Semua</option>
                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                </select>
            </div>
            <div class="faq-filter__actions">
                <button type="submit" class="btn">Terapkan</button>
                @if (request()->hasAny(['q', 'status']))
                    <a href="{{ route('superadmin.fasilitas.index') }}" class="btn gh">Reset</a>
                @endif
            </div>
        </form>
    @endunless
</div>
