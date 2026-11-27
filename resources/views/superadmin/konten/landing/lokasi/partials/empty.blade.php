<div class="lok-empty">
    <h3>{{ $isTrashed ? 'Tong sampah lokasi kosong' : 'Belum ada data lokasi kampus' }}</h3>
    <p>
        {{ $isTrashed
            ? 'Tidak ada data lokasi yang berada di tong sampah saat ini.'
            : 'Tambahkan lokasi kampus pertama untuk ditampilkan pada section Lokasi & Lingkungan Kampus.' }}
    </p>
    @unless ($isTrashed)
        <a href="{{ route('superadmin.lokasi.create') }}" class="lok-btn lok-btn--primary">
            + Tambah Lokasi Kampus
        </a>
    @endunless
</div>
