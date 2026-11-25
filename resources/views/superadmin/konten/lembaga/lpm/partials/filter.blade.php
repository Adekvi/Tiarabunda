@php
    $hasFilter = !empty(array_filter($filters ?? [], fn($v) => $v !== null && $v !== ''));
@endphp

<form method="GET" action="{{ route('superadmin.lpm.index') }}" class="card lpm-filter">
    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="lpm-input" style="flex: 1;"
        placeholder="Cari dokumen SPMI, program studi akreditasi, atau isi...">

    <select name="kategori" class="lpm-input" aria-label="Filter kategori LPM">
        <option value="">Semua Kategori</option>
        @foreach ($categories as $key => $label)
            <option value="{{ $key }}" @selected(($filters['kategori'] ?? '') === $key)>{{ $label }}</option>
        @endforeach
    </select>

    <select name="status" class="lpm-input" aria-label="Filter status">
        <option value="">Semua Status</option>
        <option value="1" @selected(($filters['status'] ?? '') === '1')>Aktif</option>
        <option value="0" @selected(($filters['status'] ?? '') === '0')>Nonaktif</option>
    </select>

    <button type="submit" class="btn">Filter</button>

    @if ($hasFilter)
        <a href="{{ route('superadmin.lpm.index') }}" class="lpm-btn-ghost">Reset</a>
    @endif
</form>
