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

    <div class="b-cards">
        @foreach ($block['items'] ?? [] as $item)
            <div class="b-card">
                @if (!empty($item['k']))
                    <span class="b-card__k">{{ $item['k'] }}</span>
                @endif
                @if (!empty($item['t']))
                    <h3 class="b-card__t">{{ $item['t'] }}</h3>
                @endif
                @if (!empty($item['d']))
                    <p class="b-card__d">{{ $item['d'] }}</p>
                @endif
            </div>
        @endforeach
    </div>
</section>
