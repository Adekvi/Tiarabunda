<div class="aka-head">
    <div>
        <h2 class="aka-head-title">
            {{ $isTrash ? 'Tempat Sampah — Layanan Akademik' : 'Daftar Dokumen Layanan Akademik' }}
        </h2>
        <p class="aka-head-sub">
            {{ $isTrash
                ? 'Dokumen yang dihapus sementara dapat dipulihkan atau dihapus permanen beserta berkasnya.'
                : 'Kelola formulir administrasi, panduan akademik, dan berkas unduhan untuk mahasiswa.' }}
        </p>
    </div>

    <div class="aka-head-acts">
        @if ($isTrash)
            <a href="{{ route('superadmin.akademik.index') }}" class="aka-btn-ghost">
                &larr; Kembali ke Daftar Aktif
            </a>
            @if ($items->isNotEmpty())
                <button type="button" class="btn aka-btn-danger" data-confirm-trigger
                    data-confirm-title="Kosongkan Tempat Sampah?"
                    data-confirm-message="Seluruh dokumen dan berkas fisik di tempat sampah akan dihapus permanen. Tindakan ini tidak dapat dibatalkan."
                    data-confirm-action="{{ route('superadmin.akademik.empty-trash') }}" data-confirm-method="DELETE">
                    Kosongkan Sampah
                </button>
            @endif
        @else
            <a href="{{ route('superadmin.akademik.trashed') }}" class="aka-btn-ghost">
                Sampah ({{ $stats['terhapus'] ?? 0 }})
            </a>
            <a href="{{ route('superadmin.akademik.create') }}" class="btn">
                + Tambah Dokumen
            </a>
        @endif
    </div>
</div>
