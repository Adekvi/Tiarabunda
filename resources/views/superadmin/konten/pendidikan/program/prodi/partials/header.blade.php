@php
    $trashed = $trashed ?? false;
@endphp

<div class="card">
    <div class="ch2">
        <div>
            <h2 style="margin-bottom:4px;">
                {{ $trashed ? 'Sampah Program Studi' : 'Kelola Program Studi' }}
            </h2>
            <p class="prodi-sub">
                {{ $trashed
                    ? 'Data terhapus. Pulihkan atau hapus permanen.'
                    : 'Program studi per fakultas yang tampil di menu Pendidikan.' }}
            </p>
        </div>

        @if ($trashed)
            <a href="{{ route('superadmin.prodi.index') }}" class="btn gh">← Kembali ke daftar</a>
        @else
            <a href="{{ route('superadmin.prodi.create') }}" class="btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                    <path d="M12 5v14" />
                    <path d="M5 12h14" />
                </svg>
                Tambah prodi
            </a>
        @endif
    </div>

    <form method="GET" action="{{ $trashed ? route('superadmin.prodi.trashed') : route('superadmin.prodi.index') }}"
        class="prodi-filter">
        <div class="f" style="margin-bottom:0;">
            <label for="q">Cari</label>
            <div class="field-icon">
                <svg class="field-icon__svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" />
                    <path d="M20 20l-3.5-3.5" />
                </svg>
                <input id="q" type="search" name="q" value="{{ request('q') }}"
                    placeholder="Nama program studi…" autocomplete="off">
            </div>
        </div>

        <div class="f" style="margin-bottom:0;">
            <label for="fakultas_id">Fakultas</label>
            <select id="fakultas_id" name="fakultas_id">
                <option value="">Semua</option>
                @foreach ($fakultasList as $f)
                    <option value="{{ $f->id }}" @selected((string) request('fakultas_id') === (string) $f->id)>
                        {{ $f->jeneng }}
                    </option>
                @endforeach
            </select>
        </div>

        @unless ($trashed)
            <div class="f" style="margin-bottom:0;">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="">Semua</option>
                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                </select>
            </div>
        @endunless

        <div class="prodi-filter__actions">
            <button type="submit" class="btn">Terapkan</button>
            @if (request()->hasAny(['q', 'status', 'fakultas_id']))
                <a href="{{ $trashed ? route('superadmin.prodi.trashed') : route('superadmin.prodi.index') }}"
                    class="btn gh">Reset</a>
            @endif
        </div>
    </form>
</div>
