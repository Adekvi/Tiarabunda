@props(['block', 'anchor' => null])

<aside @if ($anchor) id="{{ $anchor }}" @endif class="page-block b-notice rv" role="note">
    @if (!empty($block['t']))
        <h2 class="b-notice__t">{{ $block['t'] }}</h2>
    @endif

    @foreach ($block['isi'] ?? [] as $p)
        <p>{{ $p }}</p>
    @endforeach

    @if (!empty($block['daftar']))
        <ul>
            @foreach ($block['daftar'] as $x)
                <li>{{ $x }}</li>
            @endforeach
        </ul>
    @endif
</aside>
