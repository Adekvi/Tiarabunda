@if ($items->hasPages())
    <div class="faq-pager">
        <p>Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari {{ $items->total() }}</p>
        <div>{{ $items->links() }}</div>
    </div>
@endif
