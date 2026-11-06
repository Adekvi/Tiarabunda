@if ($items->hasPages())
    <div class="beasiswa-pager">
        <div class="beasiswa-pager__info">
            <span class="beasiswa-pager__range">
                Menampilkan <strong>{{ $items->firstItem() }}</strong>–<strong>{{ $items->lastItem() }}</strong>
                dari <strong>{{ number_format($items->total(), 0, ',', '.') }}</strong> data
            </span>
            <span class="beasiswa-pager__meta">
                Halaman {{ $items->currentPage() }} dari {{ $items->lastPage() }}
            </span>
        </div>

        <nav class="beasiswa-pager__nav" aria-label="Navigasi halaman">
            {{-- Previous --}}
            @if ($items->onFirstPage())
                <span class="beasiswa-pager__btn is-disabled" aria-disabled="true" aria-label="Sebelumnya">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                </span>
            @else
                <a href="{{ $items->previousPageUrl() }}" class="beasiswa-pager__btn" rel="prev"
                    aria-label="Sebelumnya">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                </a>
            @endif

            @foreach ($items->withQueryString()->links()->elements ?? [] as $element)
                @if (is_string($element))
                    <span class="beasiswa-pager__ellipsis" aria-hidden="true">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $items->currentPage())
                            <span class="beasiswa-pager__btn is-current" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="beasiswa-pager__btn">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($items->hasMorePages())
                <a href="{{ $items->nextPageUrl() }}" class="beasiswa-pager__btn" rel="next"
                    aria-label="Berikutnya">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6l6 6-6 6" />
                    </svg>
                </a>
            @else
                <span class="beasiswa-pager__btn is-disabled" aria-disabled="true" aria-label="Berikutnya">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6l6 6-6 6" />
                    </svg>
                </span>
            @endif
        </nav>
    </div>
@endif
