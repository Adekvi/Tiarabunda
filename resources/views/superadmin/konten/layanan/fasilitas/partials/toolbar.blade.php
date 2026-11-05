<div class="nw-list__header">
    <div class="nw-list__heading">
        <div class="nw-list__title-row">
            <h2 class="nw-list__title">
                {{ $trashed ? 'Sampah Fasilitas' : 'Daftar Fasilitas' }}
            </h2>

            @if ($trashed)
                <span class="nw-status nw-status--danger">
                    <span class="nw-status__dot"></span>
                    Data terhapus
                </span>
            @elseif (request('status') === 'active')
                <span class="nw-status nw-status--success">
                    <span class="nw-status__dot"></span>
                    Data aktif
                </span>
            @elseif (request('status') === 'inactive')
                <span class="nw-status nw-status--muted">
                    <span class="nw-status__dot"></span>
                    Data nonaktif
                </span>
            @endif
        </div>

        <p class="nw-list__description">
            @if ($items->total() > 0)
                Menampilkan daftar fasilitas
                {{ $filtered ? 'berdasarkan filter yang dipilih.' : 'yang tersimpan dalam sistem.' }}
            @else
                Belum ada data fasilitas yang dapat ditampilkan.
            @endif
        </p>
    </div>

    <div class="nw-list__toolbar">
        <div class="nw-total">
            <div class="nw-total__icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="16" rx="2" />
                    <path d="M8 9h8M8 13h8M8 17h4" />
                </svg>
            </div>
            <div class="nw-total__content">
                <span class="nw-total__label">Total data</span>
                <strong class="nw-total__value">{{ number_format($items->total(), 0, ',', '.') }}</strong>
            </div>
        </div>

        <span class="nw-toolbar__divider" aria-hidden="true"></span>

        <form method="GET"
            action="{{ $trashed ? route('superadmin.fasilitas.trashed') : route('superadmin.fasilitas.index') }}"
            class="nw-page-size">
            @foreach (['q', 'status'] as $key)
                @if (request()->filled($key))
                    <input type="hidden" name="{{ $key }}" value="{{ request($key) }}">
                @endif
            @endforeach

            <label for="per_page" class="nw-page-size__label">Baris</label>
            <select id="per_page" name="per_page" class="nw-page-size__select" onchange="this.form.submit()"
                aria-label="Jumlah data per halaman">
                @foreach ([10, 12, 25, 50, 100] as $n)
                    <option value="{{ $n }}" @selected((int) request('per_page', 12) === $n)>{{ $n }}</option>
                @endforeach
            </select>
            <span class="nw-page-size__suffix">/ halaman</span>
        </form>
    </div>
</div>
