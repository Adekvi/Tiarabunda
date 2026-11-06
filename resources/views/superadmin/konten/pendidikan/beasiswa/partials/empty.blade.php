<div class="beasiswa-empty">
    <div class="beasiswa-empty__ring" aria-hidden="true">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
        </svg>
    </div>
    <h3 class="beasiswa-empty__title">Belum ada beasiswa</h3>
    <p class="beasiswa-empty__desc">
        Tambahkan skema beasiswa seperti KIP Kuliah, beasiswa prestasi, atau keringanan
        agar tampil di halaman publik.
    </p>
    <a href="{{ route('superadmin.beasiswa.create') }}" class="btn">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
            stroke-linecap="round" aria-hidden="true">
            <path d="M12 5v14" />
            <path d="M5 12h14" />
        </svg>
        Tambah Beasiswa
    </a>
</div>
