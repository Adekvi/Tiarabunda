<div style="padding: 44px 20px; text-align: center;">
    <h3 style="margin: 0 0 6px; font-size: 1rem; color: var(--ink);">
        {{ $isTrash ? 'Tempat Sampah LPM Kosong' : 'Belum Ada Konten LPM' }}
    </h3>
    <p style="margin: 0 0 18px; font-size: 13px; color: var(--mut);">
        {{ $isTrash
            ? 'Tidak ada konten LPM yang dihapus sementara saat ini.'
            : 'Mulai tambahkan profil LPM, dokumen SPMI (PPEPP), laporan AMI, atau sertifikat akreditasi.' }}
    </p>
    @unless ($isTrash)
        <a href="{{ route('superadmin.lpm.create') }}" class="btn">+ Tambah Konten LPM</a>
    @endunless
</div>