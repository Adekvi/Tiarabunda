<div class="kmh-empty">
    <div class="kmh-empty__icon">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 7v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7" />
            <path d="M3 7l9-4 9 4" />
            <path d="M9 11h6" />
        </svg>
    </div>
    <p class="kmh-empty__title">
        {{ $trashed ?? false ? 'Sampah kosong' : 'Belum ada data' }}
    </p>
    <p class="kmh-empty__desc">
        {{ $trashed ?? false
            ? 'Tidak ada data terhapus saat ini.'
            : 'Tambahkan informasi kemahasiswaan untuk halaman publik.' }}
    </p>
    @unless ($trashed ?? false)
        <a href="{{ route('superadmin.kemaha.create') }}" class="btn">Tambah</a>
    @endunless
</div>
