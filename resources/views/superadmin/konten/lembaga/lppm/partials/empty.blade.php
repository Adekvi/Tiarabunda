<div style="padding: 44px 20px; text-align: center;">
    <h3 style="margin: 0 0 6px; font-size: 1rem; color: var(--ink);">
        {{ $isTrash ? 'Tempat Sampah LPPM Kosong' : 'Belum Ada Konten LPPM' }}
    </h3>
    <p style="margin: 0 0 18px; font-size: 13px; color: var(--mut);">
        {{ $isTrash
            ? 'Tidak ada konten LPPM yang dihapus sementara saat ini.'
            : 'Mulai tambahkan profil lembaga, program penelitian, pengabdian masyarakat, jurnal, atau HKI.' }}
    </p>
    @unless ($isTrash)
        <a href="{{ route('superadmin.lppm.create') }}" class="btn">+ Tambah Konten LPPM</a>
    @endunless
</div>
