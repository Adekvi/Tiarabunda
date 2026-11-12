<div class="info-hero">
    <div>
        <div class="info-hero__crumb">
            <span>Superadmin</span> <span>/</span> <span>Beranda</span> <span>/</span>
            <span class="is-current">Info Terkini</span>
        </div>
        <h1 class="info-hero__title">{{ $trashed ?? false ? 'Sampah Info' : 'Kelola Info' }}</h1>
        <p class="info-hero__desc">
            {{ $trashed ?? false ? 'Data yang dihapus sementara.' : 'Kelola pengumuman dan informasi di slider beranda.' }}
        </p>
    </div>

    @if ($trashed ?? false)
        <a href="{{ route('superadmin.info.index') }}" class="btn gh info-hero__cta">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round">
                <path d="M19 12H5" />
                <path d="m12 19-7-7 7-7" />
            </svg>
            Kembali
        </a>
    @else
        <a href="{{ route('superadmin.info.create') }}" class="btn info-hero__cta">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.2" stroke-linecap="round">
                <path d="M12 5v14" />
                <path d="M5 12h14" />
            </svg>
            Tambah Info
        </a>
    @endif
</div>
