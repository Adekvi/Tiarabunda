@if ($isTrash)
    <div class="card" style="padding: 12px 16px; border-left: 4px solid #d97706; font-size: 13px; color: var(--ink);">
        Anda sedang melihat <b>Tempat Sampah LPPM</b> ({{ $stats['terhapus'] ?? 0 }} item).
        Konten di sini tidak tampil di halaman publik sampai dipulihkan.
    </div>
@elseif (($stats['terhapus'] ?? 0) > 0)
    <div class="card"
        style="padding: 10px 16px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: space-between; font-size: 13px;">
        <span style="color: var(--mut);">
            Terdapat <b style="color: var(--ink);">{{ $stats['terhapus'] }} konten LPPM</b> di dalam tempat sampah.
        </span>
        <a href="{{ route('superadmin.lppm.trashed') }}" style="font-weight: 700; color: #1f5fbf; text-decoration: none;">
            Kelola Sampah &rarr;
        </a>
    </div>
@endif
