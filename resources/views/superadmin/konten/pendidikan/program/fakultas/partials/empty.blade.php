{{-- @vars: $trashed (bool), $filtered (bool) --}}
<div class="faq-empty">
    @if ($trashed)
        <p class="faq-empty__title">Sampah kosong</p>
        <p class="faq-empty__desc">Tidak ada fakultas yang dihapus.</p>
        <a href="{{ route('superadmin.fakultas.index') }}" class="btn gh">← Kembali</a>
    @elseif ($filtered)
        <p class="faq-empty__title">Tidak ada hasil</p>
        <p class="faq-empty__desc">Coba ubah kata kunci atau filter status.</p>
        <a href="{{ route('superadmin.fakultas.index') }}" class="btn gh">Reset filter</a>
    @else
        <p class="faq-empty__title">Belum ada fakultas</p>
        <p class="faq-empty__desc">Tambahkan fakultas sebelum mengisi program studi.</p>
        <a href="{{ route('superadmin.fakultas.create') }}" class="btn">Tambah fakultas</a>
    @endif
</div>
