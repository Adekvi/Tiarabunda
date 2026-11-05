@if ($items->hasPages() || $items->total() > 0)
    <div class="nw-pagination">

        {{-- Informasi rentang data --}}
        <div class="nw-pagination__info">
            @if ($items->total() > 0)
                <span class="nw-pagination__text">
                    Menampilkan
                </span>

                <strong>{{ number_format($items->firstItem(), 0, ',', '.') }}</strong>

                <span class="nw-pagination__text">–</span>

                <strong>{{ number_format($items->lastItem(), 0, ',', '.') }}</strong>

                <span class="nw-pagination__text">
                    dari
                </span>

                <strong>{{ number_format($items->total(), 0, ',', '.') }}</strong>

                <span class="nw-pagination__text">
                    data
                </span>
            @else
                <span class="nw-pagination__text">
                    Tidak ada data untuk ditampilkan.
                </span>
            @endif
        </div>

        {{-- Navigasi halaman --}}
        @if ($items->hasPages())
            <div class="nw-pagination__navigation">
                {{ $items->onEachSide(1)->links() }}
            </div>
        @endif

    </div>
@endif
