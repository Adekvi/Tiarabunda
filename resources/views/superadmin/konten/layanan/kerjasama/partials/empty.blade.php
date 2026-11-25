<div style="padding: 44px 20px; text-align: center;">
    <h3 style="margin: 0 0 6px; font-size: 1rem; color: var(--ink);">
        {{ $isTrash ? 'Tempat Sampah Kerjasama Kosong' : 'Belum Ada Data Layanan Kerjasama' }}
    </h3>
    <p style="margin: 0 0 18px; font-size: 13px; color: var(--mut);">
        {{ $isTrash
            ? 'Tidak ada data kerjasama yang dihapus sementara saat ini.'
            : 'Tambahkan mitra instansi/perusahaan atau skema penawaran kerjasama untuk dipublikasikan.' }}
    </p>
    @unless ($isTrash)
        <a href="{{ route('superadmin.kerjasama.create') }}" class="btn">+ Tambah Kerjasama Pertama</a>
    @endunless
</div>
