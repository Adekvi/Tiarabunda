<div class="card sej-hero">
    <div class="sej-hero__main">
        <p class="sej-hero__crumb">
            <span>Profil</span>
            <span aria-hidden="true">/</span>
            <span class="is-current">Sejarah</span>
        </p>
        <h2 class="sej-hero__title">Kelola Sejarah Kampus</h2>
        <p class="sej-hero__desc">
            Narasi perjalanan kampus yang tampil di menu <strong>Profil → Sejarah</strong>.
            Hanya konten berstatus <strong>aktif</strong> yang akan ditampilkan di situs publik.
        </p>
    </div>
    <a href="{{ route('superadmin.sejarah.create') }}" class="btn sej-hero__cta">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
            stroke-linecap="round" aria-hidden="true">
            <path d="M12 5v14" />
            <path d="M5 12h14" />
        </svg>
        Tambah Konten
    </a>
</div>
