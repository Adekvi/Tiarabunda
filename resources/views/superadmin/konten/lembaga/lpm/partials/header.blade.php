<div class="lpm-head">
    <div>
        <h2 class="lpm-head-title">
            {{ $isTrash ? 'Tempat Sampah — LPM' : 'Dokumen & Program Penjaminan Mutu (LPM)' }}
        </h2>
        <p class="lpm-head-sub">
            {{ $isTrash
                ? 'Konten LPM yang dihapus sementara dapat dipulihkan atau dihapus permanen.'
                : 'Kelola dokumen SPMI (PPEPP), Audit Mutu Internal (AMI), akreditasi, dan survei kepuasan.' }}
        </p>
    </div>

    <div class="lpm-head-acts">
        @if ($isTrash)
            <a href="{{ route('superadmin.lpm.index') }}" class="lpm-btn-ghost">
                &larr; Kembali ke Daftar Aktif
            </a>
            @if ($items->isNotEmpty())
                <button type="button" class="btn lpm-btn-danger" data-confirm-trigger
                    data-confirm-title="Kosongkan Tempat Sampah LPM?"
                    data-confirm-message="Seluruh konten LPM beserta berkasnya di tempat sampah akan dihapus permanen."
                    data-confirm-action="{{ route('superadmin.lpm.empty-trash') }}" data-confirm-method="DELETE">
                    Kosongkan Sampah
                </button>
            @endif
        @else
            <a href="{{ route('superadmin.lpm.trashed') }}" class="lpm-btn-ghost">
                Sampah ({{ $stats['terhapus'] ?? 0 }})
            </a>
            <a href="{{ route('superadmin.lpm.create') }}" class="btn">
                + Tambah Konten LPM
            </a>
        @endif
    </div>
</div>
