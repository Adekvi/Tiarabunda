@props(['block', 'anchor' => null])

@php
    $isVision = strtolower(trim($block['t'] ?? '')) === 'visi';
    $isMission = strtolower(trim($block['t'] ?? '')) === 'misi';
    $hasList = !empty($block['daftar']);
@endphp

<article @if ($anchor) id="{{ $anchor }}" @endif @class([
    'page-block',
    'rv',
    'b-vision' => $isVision,
    'b-mission' => $isMission && $hasList,
])>
    @if (!empty($block['t']))
        <h2>{{ $block['t'] }}</h2>
    @endif

    @if ($isVision && !empty($block['isi'][0]))
        <blockquote class="b-quote">
            <p>{{ $block['isi'][0] }}</p>
        </blockquote>
    @else
        @foreach ($block['isi'] ?? [] as $p)
            <p>{{ $p }}</p>
        @endforeach
    @endif

    @if ($hasList)
        <ol @class(['b-mission__list' => $isMission])>
            @foreach ($block['daftar'] as $i => $x)
                <li>
                    @if ($isMission)
                        <span class="b-mission__num">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    @endif
                    <span class="b-mission__text">{{ $x }}</span>
                </li>
            @endforeach
        </ol>
    @endif
</article>
