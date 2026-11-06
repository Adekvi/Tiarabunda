@if ($items->hasPages())
    <div class="faq-pager">
        <p>
            Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }}
            dari {{ $items->total() }}
        </p>
        <div class="faq-pager__nav">
            {{-- Pakai view default dari AppServiceProvider (class .pg) --}}
            {{ $items->withQueryString()->links() }}
        </div>
    </div>
@endif
