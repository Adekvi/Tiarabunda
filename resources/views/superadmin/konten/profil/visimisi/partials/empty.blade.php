<div class="vis-empty">
    <div class="vis-empty__ring" aria-hidden="true">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
            stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 8v4" />
            <path d="M12 16h.01" />
        </svg>
    </div>
    <h3 class="vis-empty__title">Belum ada visi atau misi</h3>
    <p class="vis-empty__desc">
        Tambahkan <strong>visi</strong> (narasi tujuan jangka panjang) dan
        <strong>misi</strong> (daftar poin komitmen) agar tampil di halaman Profil → Visi &amp; Misi.
    </p>
    <a href="{{ route('superadmin.visimisi.create') }}" class="btn">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
            stroke-linecap="round" aria-hidden="true">
            <path d="M12 5v14" />
            <path d="M5 12h14" />
        </svg>
        Tambah data pertama
    </a>
</div>
