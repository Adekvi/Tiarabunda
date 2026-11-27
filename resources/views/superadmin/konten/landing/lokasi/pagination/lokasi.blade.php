@if ($paginator->hasPages())
    <div>
        Menampilkan <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
        dari <strong>{{ $paginator->total() }}</strong> data
    </div>

    <ul class="lok-pagi-list">
        @if ($paginator->onFirstPage())
            <li><span class="lok-pagi-link is-disabled">&laquo;</span></li>
        @else
            <li><a class="lok-pagi-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li><span class="lok-pagi-link is-disabled">{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li><span class="lok-pagi-link is-active">{{ $page }}</span></li>
                    @else
                        <li><a class="lok-pagi-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li><a class="lok-pagi-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
        @else
            <li><span class="lok-pagi-link is-disabled">&raquo;</span></li>
        @endif
    </ul>
@endif
