<div class="kjs-head">
    <div>
        <h2 class="kjs-head-title">
            {{ $isTrash ? 'Tempat Sampah — Layanan Kerjasama' : 'Daftar Mitra & Penawaran Kerjasama' }}
        </h2>
        <p class="kjs-head-sub">
            {{ $isTrash
                ? 'Data kerjasama yang dihapus sementara dapat dipulihkan atau dihapus permanen.'
                : 'Kelola daftar mitra instansi/perusahaan serta program penawaran kerjasama Universitas Tiara Bunda.' }}
        </p>
    </div>

    <div class="kjs-head-acts">
        @if ($isTrash)
            <a href="{{ route('superadmin.kerjasama.index') }}" class="kjs-btn-ghost">
                &larr; Kembali ke Daftar Aktif
            </a>
            @if ($items->isNotEmpty())
                <button type="button" class="btn kjs-btn-danger" data-confirm-trigger
                    data-confirm-title="Kosongkan Tempat Sampah?"
                    data-confirm-message="Seluruh data kerjasama, logo, dan berkas di tempat sampah akan dihapus permanen."
                    data-confirm-action="{{ route('superadmin.kerjasama.empty-trash') }}" data-confirm-method="DELETE">
                    Kosongkan Sampah
                </button>
            @endif
        @else
            <a href="{{ route('superadmin.kerjasama.trashed') }}" class="kjs-btn-ghost">
                Sampah ({{ $stats['terhapus'] ?? 0 }})
            </a>
            <a href="{{ route('superadmin.kerjasama.create') }}" class="btn">
                + Tambah Kerjasama
            </a>
        @endif
    </div>
</div>
