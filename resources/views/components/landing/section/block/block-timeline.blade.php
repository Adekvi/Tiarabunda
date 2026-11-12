@props(['block', 'anchor' => null])

<section @if ($anchor) id="{{ $anchor }}" @endif class="page-block rv">
    @if (!empty($block['t']))
        <h2>{{ $block['t'] }}</h2>
    @endif

    @if (!empty($block['isi']))
        @foreach ($block['isi'] as $p)
            <p>{{ $p }}</p>
        @endforeach
    @endif

    <ol class="b-timeline">
        @foreach ($block['items'] ?? [] as $item)
            <li class="b-timeline__item">
                @if (!empty($item['k']))
                    <span class="b-timeline__k">{{ $item['k'] }}</span>
                @endif
                @if (!empty($item['t']))
                    <h3 class="b-timeline__t">{{ $item['t'] }}</h3>
                @endif
                @if (!empty($item['d']))
                    <p class="b-timeline__d">{{ $item['d'] }}</p>
                @endif
            </li>
        @endforeach
    </ol>
</section>
