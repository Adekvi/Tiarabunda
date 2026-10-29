@props(['items' => config('landing.home.berita')])
@php($f = $items[0])
<section class="sec" id="berita">
    <div class="wrap">
        <x-landing.ui.head kick="Berita & kegiatan" title="Kabar terbaru dari kampus" />
        <div class="news rv">
            <article class="nf"><span class="tag">{{ $f['j'] }}</span>
                <h3>{{ $f['t'] }}</h3><time>{{ $f['tgl'] }}</time>
            </article>
            <div class="nl">
                @foreach (array_slice($items, 1) as $n)
                    <article><span class="tag">{{ $n['j'] }}</span>
                        <h3>{{ $n['t'] }}</h3><time>{{ $n['tgl'] }}</time>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
