<div class="card pmb-hero">
    <div class="pmb-hero__main">
        <p class="pmb-hero__crumb">
            <span>Pendidikan</span>
            <span aria-hidden="true">/</span>
            <span class="is-current">PMB</span>
        </p>
        <h2 class="pmb-hero__title">Kelola Jalur PMB</h2>
        <p class="pmb-hero__desc">
            Kelola informasi jalur Penerimaan Mahasiswa Baru (PMB) yang tampil di situs publik.
            Hanya jalur <strong>aktif</strong> yang akan ditampilkan.
        </p>
    </div>
    <a href="{{ route('superadmin.pmb.create') }}" class="btn pmb-hero__cta">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
            stroke-linecap="round" aria-hidden="true">
            <path d="M12 5v14" />
            <path d="M5 12h14" />
        </svg>
        Tambah Jalur
    </a>
</div>
