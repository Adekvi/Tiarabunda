<div class="tst-empty">
    @if ($isTrash)
        <p>Tong sampah testimoni kosong.</p>
    @elseif ($q !== '' || $kategori !== '' || $status !== '')
        <p>Tidak ditemukan data testimoni yang sesuai dengan filter saat ini.</p>
        <a href="{{ route('superadmin.testimoni.index') }}" class="lok-btn lok-btn--ghost">
            Reset Filter
        </a>
    @else
        <p>Belum ada data testimoni yang ditambahkan.</p>
        <a href="{{ route('superadmin.testimoni.create') }}" class="lok-btn lok-btn--primary">
            + Tambah Testimoni Pertama
        </a>
    @endif
</div>
