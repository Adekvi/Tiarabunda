<div class="lok-head">
    <div>
        <nav class="lok-crumb" aria-label="Breadcrumb">
            <a href="{{ route('superadmin.dashboard') }}">Dashboard</a>
            <span>/</span>
            <span>Testimoni</span>
        </nav>
        <h1 class="lok-title">Testimoni Mahasiswa &amp; Alumni</h1>
        <p class="lok-sub">Kelola cerita pengalaman akademik, beasiswa, dan karier lulusan yang tampil di beranda.</p>
    </div>

    <div class="lok-head-actions">
        @if ($isTrash)
            <a href="{{ route('superadmin.testimoni.index') }}" class="lok-btn lok-btn--ghost">
                &larr; Kembali ke Data Aktif
            </a>
        @endif
        <a href="{{ route('superadmin.testimoni.create') }}" class="lok-btn lok-btn--primary">
            + Tambah Testimoni
        </a>
    </div>
</div>
