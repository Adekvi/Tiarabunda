@if ($items->hasPages())
    <div style="padding: 14px 16px; border-top: 1px solid var(--line);">
        {{ $items->links('superadmin.konten.layanan.akademik.pagination.aka') }}
    </div>
@endif
