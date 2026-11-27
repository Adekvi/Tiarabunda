<div class="card tst-filter-card">
    <form method="GET" action="{{ route('superadmin.testimoni.index') }}" class="tst-filter-form">
        @if ($status !== '')
            <input type="hidden" name="status" value="{{ $status }}">
        @endif

        <input type="search" name="q" value="{{ $q }}" class="tst-fcontrol"
            placeholder="Cari nama, peran, angkatan, instansi, sorotan, atau kutipan...">

        <select name="kategori" class="tst-fcontrol" aria-label="Filter kategori">
            <option value="">Semua Kategori</option>
            @foreach (\App\Models\Landing\Testimoni::KATEGORI as $key => $label)
                <option value="{{ $key }}" @selected($kategori === $key)>{{ $label }}</option>
            @endforeach
        </select>

        <div class="tst-filter-btns">
            <button type="submit" class="lok-btn lok-btn--primary">Cari</button>
            @if ($q !== '' || $kategori !== '')
                <a href="{{ route('superadmin.testimoni.index', $status !== '' ? ['status' => $status] : []) }}"
                    class="lok-btn lok-btn--ghost">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>
