@if ($items->hasPages())
    <div class="prodi-pager">

        {{-- Ringkasan jumlah data --}}
        <div class="prodi-pager__info">
            <span class="prodi-pager__range">
                Menampilkan <strong>{{ $items->firstItem() }}</strong>–<strong>{{ $items->lastItem() }}</strong>
                dari <strong>{{ number_format($items->total(), 0, ',', '.') }}</strong> data
            </span>
            <span class="prodi-pager__meta">
                Halaman {{ $items->currentPage() }} dari {{ $items->lastPage() }}
            </span>
        </div>

        <nav class="prodi-pager__nav" aria-label="Navigasi halaman">

            {{-- Prev --}}
            @if ($items->onFirstPage())
                <span class="prodi-pager__btn is-disabled" aria-disabled="true" aria-label="Halaman sebelumnya">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                </span>
            @else
                <a href="{{ $items->previousPageUrl() }}" class="prodi-pager__btn" rel="prev"
                    aria-label="Halaman sebelumnya">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                </a>
            @endif

            {{-- Nomor halaman (dengan ellipsis) --}}
            @foreach ($items->withQueryString()->links()->elements ?? [] as $element)
                @if (is_string($element))
                    <span class="prodi-pager__ellipsis" aria-hidden="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $items->currentPage())
                            <span class="prodi-pager__btn is-current" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="prodi-pager__btn">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($items->hasMorePages())
                <a href="{{ $items->nextPageUrl() }}" class="prodi-pager__btn" rel="next"
                    aria-label="Halaman berikutnya">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6l6 6-6 6" />
                    </svg>
                </a>
            @else
                <span class="prodi-pager__btn is-disabled" aria-disabled="true" aria-label="Halaman berikutnya">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6l6 6-6 6" />
                    </svg>
                </span>
            @endif
        </nav>
    </div>
@endif
