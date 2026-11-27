<div class="card lok-filter">
    <form action="{{ $isTrashed ? route('superadmin.lokasi.trashed') : route('superadmin.lokasi.index') }}" method="GET"
        class="lok-filter-form">
        <input type="text" name="search" class="lok-input lok-filter-search"
            placeholder="Cari kode, nama kampus, badge, atau alamat..." value="{{ request('search') }}">

        @unless ($isTrashed)
            <select name="status" class="lok-select lok-filter-status">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif (Tampil)</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        @endunless

        <button type="submit" class="lok-btn lok-btn--primary">Filter</button>

        @if (request()->hasAny(['search', 'status']))
            <a href="{{ $isTrashed ? route('superadmin.lokasi.trashed') : route('superadmin.lokasi.index') }}"
                class="lok-btn lok-btn--ghost">Reset</a>
        @endif
    </form>
</div>
