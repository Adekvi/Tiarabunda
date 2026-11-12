@if (!($trashed ?? false))
    <form method="GET" action="{{ route('superadmin.info.index') }}" class="info-filter card">
        <div class="f" style="margin-bottom:0;">
            <label for="q">Cari</label>
            <div class="field-icon">
                <svg class="field-icon__svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <circle cx="11" cy="11" r="7" />
                    <path d="M20 20l-3.5-3.5" />
                </svg>
                <input id="q" type="search" name="search" value="{{ request('search') }}"
                    placeholder="Cari judul atau kategori…" autocomplete="off">
            </div>
        </div>
        <div class="f" style="margin-bottom:0;min-width:150px;">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">Semua Status</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="draft" @selected(request('status') === 'draft')>Draf</option>
            </select>
        </div>
        <div class="info-filter__actions">
            <button type="submit" class="btn">Terapkan</button>
            @if (request()->hasAny(['search', 'status']))
                <a href="{{ route('superadmin.info.index') }}" class="btn gh">Reset</a>
            @endif
        </div>
    </form>
@endif
