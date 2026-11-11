@if ($items->hasPages())
    <div class="prp-pager">
        <div class="prp-pager__info">
            Menampilkan <strong>{{ $items->firstItem() }}</strong>–<strong>{{ $items->lastItem() }}</strong>
            dari <strong>{{ $items->total() }}</strong> data
        </div>

        <div class="prp-pager__nav">
            {{ $items->withQueryString()->links('superadmin.konten.layanan.perpus.pagination.prp') }}
        </div>
    </div>
@endif
