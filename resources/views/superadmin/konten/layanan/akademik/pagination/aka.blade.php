@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman Akademik"
         style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: space-between; font-size: 13px;">
        <div style="color: var(--mut);">
            Menampilkan <b>{{ $paginator->firstItem() }}</b>–<b>{{ $paginator->lastItem() }}</b>
            dari <b>{{ $paginator->total() }}</b> dokumen
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 4px; align-items: center;">
            @if ($paginator->onFirstPage())
                <span class="aka-act" style="opacity: .45; cursor: not-allowed;">&lsaquo;</span>
            @else
                <a class="aka-act" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="aka-act" style="border: 0;">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="aka-act" style="background: #1f5fbf; border-color: #1f5fbf; color: #fff;">
                                {{ $page }}
                            </span>
                        @else
                            <a class="aka-act" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="aka-act" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a>
            @else
                <span class="aka-act" style="opacity: .45; cursor: not-allowed;">&rsaquo;</span>
            @endif
        </div>
    </nav>
@endif