<form method="GET" action="{{ route('superadmin.visimisi.index') }}" class="card vis-filter"
    style="grid-template-columns:1fr minmax(120px,150px) minmax(120px,150px) auto;">
    <div class="f" style="margin-bottom:0;">
        <label for="q">Cari</label>
        <div class="field-icon">
            <svg class="field-icon__svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <circle cx="11" cy="11" r="7" />
                <path d="M20 20l-3.5-3.5" />
            </svg>
            <input id="q" type="search" name="q" value="{{ request('q') }}"
                placeholder="Cari judul atau isi…" autocomplete="off">
        </div>
    </div>

    <div class="f" style="margin-bottom:0;">
        <label for="bagean">Bagian</label>
        <select id="bagean" name="bagean">
            <option value="">Semua</option>
            <option value="visi" @selected(request('bagean') === 'visi')>Visi</option>
            <option value="misi" @selected(request('bagean') === 'misi')>Misi</option>
        </select>
    </div>

    <div class="f" style="margin-bottom:0;">
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="">Semua</option>
            <option value="active" @selected(request('status') === 'active')>Aktif</option>
            <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
        </select>
    </div>

    <div class="vis-filter__actions">
        <button type="submit" class="btn">Terapkan</button>
        @if (request()->hasAny(['q', 'status', 'bagean']))
            <a href="{{ route('superadmin.visimisi.index') }}" class="btn gh">Reset</a>
        @endif
    </div>
</form>
