@if ($trashed ?? false)
    <div class="krr-empty">
        <div class="krr-empty__ring">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18" />
                <path d="M8 6V4h8v2" />
                <path d="M19 6l-1 14H6L5 6" />
            </svg>
        </div>
        <h3 class="krr-empty__title">Sampah kosong</h3>
        <p class="krr-empty__desc">Tidak ada data yang dihapus. Item yang Anda hapus akan muncul di sini.</p>
        <a href="{{ route('superadmin.karir.index') }}" class="btn gh">Kembali ke daftar</a>
    </div>
@else
    <div class="krr-empty">
        <div class="krr-empty__ring">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
        </div>
        <h3 class="krr-empty__title">Belum Ada Data</h3>
        <p class="krr-empty__desc">Tambahkan profil data, jam layanan, atau tautan.</p>
        <a href="{{ route('superadmin.karir.create') }}" class="btn">Tambah Data</a>
    </div>
@endif
