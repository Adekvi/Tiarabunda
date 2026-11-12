@if ($items->hasPages())
    <div class="info-pager">
        <div class="info-pager__info">
            Menampilkan <strong>{{ $items->firstItem() }}</strong>–<strong>{{ $items->lastItem() }}</strong>
            dari <strong>{{ $items->total() }}</strong> data
        </div>
        <div class="info-pager__nav">
            {{ $items->withQueryString()->links('superadmin.konten.landing.info.page.pagination') }}
        </div>
    </div>
@endif
