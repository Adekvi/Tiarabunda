@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="krr-pager-nav">
        <ul class="krr-pager-list">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="krr-pager-item is-disabled" aria-disabled="true">
                    <span class="krr-pager-link" aria-hidden="true">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span class="krr-pager-text">Sebelumnya</span>
                    </span>
                </li>
            @else
                <li class="krr-pager-item">
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="krr-pager-link"
                        aria-label="Halaman sebelumnya">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span class="krr-pager-text">Sebelumnya</span>
                    </a>
                </li>
            @endif

            {{-- Page numbers (dengan ellipsis) --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="krr-pager-item is-disabled" aria-disabled="true">
                        <span class="krr-pager-link">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="krr-pager-item is-active" aria-current="page">
                                <span class="krr-pager-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="krr-pager-item">
                                <a href="{{ $url }}" class="krr-pager-link"
                                    aria-label="Halaman {{ $page }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="krr-pager-item">
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="krr-pager-link"
                        aria-label="Halaman berikutnya">
                        <span class="krr-pager-text">Berikutnya</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                </li>
            @else
                <li class="krr-pager-item is-disabled" aria-disabled="true">
                    <span class="krr-pager-link" aria-hidden="true">
                        <span class="krr-pager-text">Berikutnya</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
