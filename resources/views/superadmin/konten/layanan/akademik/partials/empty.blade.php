<div style="padding: 44px 20px; text-align: center;">
    <h3 style="margin: 0 0 6px; font-size: 1rem; color: var(--ink);">
        {{ $isTrash ? 'Tempat Sampah Akademik Kosong' : 'Belum Ada Dokumen Layanan Akademik' }}
    </h3>
    <p style="margin: 0 0 18px; font-size: 13px; color: var(--mut);">
        {{ $isTrash
            ? 'Tidak ada dokumen layanan akademik yang dihapus sementara saat ini.'
            : 'Tambahkan formulir KRS, cuti, surat keterangan, atau panduan akademik untuk mahasiswa.' }}
    </p>
    @unless ($isTrash)
        <a href="{{ route('superadmin.akademik.create') }}" class="btn">+ Tambah Dokumen Pertama</a>
    @endunless
</div>
