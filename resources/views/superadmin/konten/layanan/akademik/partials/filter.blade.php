@php
    $hasFilter = !empty(array_filter($filters ?? [], fn($v) => $v !== null && $v !== ''));
@endphp

<form method="GET" action="{{ route('superadmin.akademik.index') }}" class="card aka-filter">
    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="aka-input" style="flex: 1;"
        placeholder="Cari nama dokumen, slug tandha, atau keterangan...">

    <select name="kategori" class="aka-input" aria-label="Filter kategori">
        <option value="">Semua Kategori</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat }}" @selected(($filters['kategori'] ?? '') === $cat)>{{ $cat }}</option>
        @endforeach
    </select>

    <select name="status" class="aka-input" aria-label="Filter status">
        <option value="">Semua Status</option>
        <option value="1" @selected(($filters['status'] ?? '') === '1')>Aktif (Publik)</option>
        <option value="0" @selected(($filters['status'] ?? '') === '0')>Nonaktif (Draft)</option>
    </select>

    <button type="submit" class="btn">Filter</button>

    @if ($hasFilter)
        <a href="{{ route('superadmin.akademik.index') }}" class="aka-btn-ghost">Reset</a>
    @endif
</form>
