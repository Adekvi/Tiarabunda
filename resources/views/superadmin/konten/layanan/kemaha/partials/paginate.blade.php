@if ($items->hasPages())
    <div class="kmh-pager">
        <div class="kmh-pager__info">
            Menampilkan <strong>{{ $items->firstItem() }}</strong>–<strong>{{ $items->lastItem() }}</strong>
            dari <strong>{{ $items->total() }}</strong> data
        </div>

        <div class="kmh-pager__nav">
            {{ $items->withQueryString()->links('superadmin.konten.layanan.kemaha.pagination.kmh') }}
        </div>
    </div>
@endif
