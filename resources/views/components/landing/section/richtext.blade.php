@props(['blok'])
<section class="sec">
    <div class="wrap doc">
        <aside class="toc" aria-label="Daftar isi">
            @foreach ($blok as $b)
                <a href="#{{ $b['id'] }}">{{ $b['t'] }}</a>
            @endforeach
        </aside>
        <div class="prose">
            @foreach ($blok as $b)
                <article id="{{ $b['id'] }}" class="rv">
                    <h2>{{ $b['t'] }}</h2>
                    @foreach ($b['isi'] ?? [] as $p)
                        <p>{{ $p }}</p>
                    @endforeach
                    @if (!empty($b['daftar']))
                        <ul>
                            @foreach ($b['daftar'] as $x)
                                <li>{{ $x }}</li>
                            @endforeach
                        </ul>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
