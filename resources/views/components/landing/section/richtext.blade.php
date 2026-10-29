@props(['block'])
<section class="sec">
    <div class="wrap single prose">
        <article class="rv">
            @if (!empty($block['t']))
                <h2>{{ $block['t'] }}</h2>
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
        </article>
    </div>
</section>
