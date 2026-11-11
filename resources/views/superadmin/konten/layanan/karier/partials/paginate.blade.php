@if ($items->hasPages())
    <div class="krr-pager">
        <div class="krr-pager__info">
            Menampilkan <strong>{{ $items->firstItem() }}</strong>–<strong>{{ $items->lastItem() }}</strong>
            dari <strong>{{ $items->total() }}</strong> data
        </div>

        <div class="krr-pager__nav">
            {{ $items->withQueryString()->links('superadmin.konten.layanan.karier.pagination.krr') }}
        </div>
    </div>
@endif
