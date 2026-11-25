@php
    $hasFilter = !empty(array_filter($filters ?? [], fn($v) => $v !== null && $v !== ''));
@endphp

<form method="GET" action="{{ route('superadmin.kerjasama.index') }}" class="card kjs-filter">
    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="kjs-input" style="flex: 1;"
        placeholder="Cari nama instansi, penawaran, atau nomor MoU...">

    <select name="jenis" class="kjs-input" aria-label="Filter jenis">
        <option value="">Semua Jenis</option>
        <option value="mitra" @selected(($filters['jenis'] ?? '') === 'mitra')>Mitra Instansi / Perusahaan</option>
        <option value="penawaran" @selected(($filters['jenis'] ?? '') === 'penawaran')>Penawaran Kerjasama</option>
    </select>

    <select name="kategori" class="kjs-input" aria-label="Filter kategori">
        <option value="">Semua Sektor</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat }}" @selected(($filters['kategori'] ?? '') === $cat)>{{ $cat }}</option>
        @endforeach
    </select>

    <select name="status" class="kjs-input" aria-label="Filter status">
        <option value="">Semua Status</option>
        <option value="1" @selected(($filters['status'] ?? '') === '1')>Aktif</option>
        <option value="0" @selected(($filters['status'] ?? '') === '0')>Nonaktif</option>
    </select>

    <button type="submit" class="btn">Filter</button>

    @if ($hasFilter)
        <a href="{{ route('superadmin.kerjasama.index') }}" class="kjs-btn-ghost">Reset</a>
    @endif
</form>
