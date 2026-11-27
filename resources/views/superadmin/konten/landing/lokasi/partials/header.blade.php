<div class="lok-head">
    <div>
        <nav class="lok-crumb" aria-label="Breadcrumb">
            <a href="{{ route('superadmin.dashboard') }}">Dashboard</a>
            <span>/</span>
            <a href="{{ route('superadmin.lokasi.index') }}">Lokasi Kampus</a>
            @if ($isTrashed)
                <span>/</span>
                <span>Tong Sampah</span>
            @endif
        </nav>
        <h1 class="lok-title">
            {{ $isTrashed ? 'Tong Sampah — Lokasi Kampus' : 'Lokasi & Lingkungan Kampus' }}
        </h1>
        <p class="lok-sub">
            {{ $isTrashed
                ? 'Data lokasi kampus yang dihapus sementara. Pulihkan atau hapus permanen dari sini.'
                : 'Kelola gedung kampus, foto arsitektur, alamat, kontak layanan, dan peta Google Maps.' }}
        </p>
    </div>

    <div class="lok-head-actions">
        @if ($isTrashed)
            <a href="{{ route('superadmin.lokasi.index') }}" class="lok-btn lok-btn--ghost">
                &larr; Kembali ke Daftar
            </a>
            @if ($trashedCount > 0)
                <button type="button" class="lok-btn lok-btn--danger" data-confir-open
                    data-action="{{ route('superadmin.lokasi.empty-trash') }}" data-method="DELETE"
                    data-title="Kosongkan Tong Sampah?"
                    data-message="Semua data lokasi di tong sampah beserta file fotonya akan dihapus secara permanen."
                    data-btn-class="lok-btn lok-btn--danger" data-btn-text="Ya, Kosongkan">
                    Kosongkan Sampah ({{ $trashedCount }})
                </button>
            @endif
        @else
            <a href="{{ route('superadmin.lokasi.trashed') }}" class="lok-btn lok-btn--ghost">
                Tong Sampah
                @if ($trashedCount > 0)
                    <span class="lok-badge lok-badge--err">{{ $trashedCount }}</span>
                @endif
            </a>
            <a href="{{ route('superadmin.lokasi.create') }}" class="lok-btn lok-btn--primary">
                + Tambah Lokasi
            </a>
        @endif
    </div>
</div>
