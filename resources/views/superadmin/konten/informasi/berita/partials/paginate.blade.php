@if ($items->hasPages())
    <div class="faq-pager nw-pager">
        <p class="nw-pager__info">
            Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }}
            dari {{ $items->total() }}
            · Halaman {{ $items->currentPage() }} / {{ $items->lastPage() }}
        </p>
        <div class="nw-pager__links">
            {{ $items->onEachSide(1)->links() }}
        </div>
    </div>
@endif
