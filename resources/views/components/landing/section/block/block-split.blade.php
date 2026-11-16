@props(['block', 'anchor' => null])

<section @if ($anchor) id="{{ $anchor }}" @endif class="page-block b-split rv">
    <div class="b-split__body">
        @if (! empty($block['t']))
            <h2>{{ $block['t'] }}</h2>
        @endif

        @foreach ($block['isi'] ?? [] as $p)
            <p>{{ $p }}</p>
        @endforeach

        @if (! empty($block['daftar']))
            <ul>
                @foreach ($block['daftar'] as $x)
                    <li>{{ $x }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    @if (! empty($block['img']))
        <figure class="b-split__media">
            <img src="{{ str_starts_with($block['img'], 'http') ? $block['img'] : asset($block['img']) }}"
                alt="{{ $block['t'] ?? '' }}" loading="lazy" decoding="async" width="640" height="420">
        </figure>
    @endif
</section>