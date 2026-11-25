<div class="lppm-head">
    <div>
        <h2 class="lppm-head-title">
            {{ $isTrash ? 'Tempat Sampah — LPPM' : 'Program & Layanan LPPM' }}
        </h2>
        <p class="lppm-head-sub">
            {{ $isTrash
                ? 'Konten LPPM yang dihapus sementara dapat dipulihkan atau dihapus permanen.'
                : 'Kelola profil lembaga, penelitian, pengabdian masyarakat, publikasi jurnal, dan HKI.' }}
        </p>
    </div>

    <div class="lppm-head-acts">
        @if ($isTrash)
            <a href="{{ route('superadmin.lppm.index') }}" class="lppm-btn-ghost">
                &larr; Kembali ke Daftar Aktif
            </a>
            @if ($items->isNotEmpty())
                <button type="button" class="btn lppm-btn-danger" data-confirm-trigger
                    data-confirm-title="Kosongkan Tempat Sampah LPPM?"
                    data-confirm-message="Seluruh konten LPPM di dalam tempat sampah akan dihapus permanen."
                    data-confirm-action="{{ route('superadmin.lppm.empty-trash') }}" data-confirm-method="DELETE">
                    Kosongkan Sampah
                </button>
            @endif
        @else
            <a href="{{ route('superadmin.lppm.trashed') }}" class="lppm-btn-ghost">
                Sampah ({{ $stats['terhapus'] ?? 0 }})
            </a>
            <a href="{{ route('superadmin.lppm.create') }}" class="btn">
                + Tambah Konten LPPM
            </a>
        @endif
    </div>
</div>
