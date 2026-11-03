@props(['items' => []])

@php
    if ($items instanceof \Illuminate\Support\Collection) {
        $items = $items->all();
    }
    if ($items === [] || $items === null) {
        $items = app(\App\Services\Landing\LandingHomeService::class)->beritas()->all();
    }
    if ($items === []) {
        $items = config('landing.home.berita', []);
    }
    $items = array_values(is_array($items) ? $items : []);
@endphp

@if (count($items))
    @php($f = $items[0])
    <section class="sec" id="berita">
        <div class="wrap">
            <x-landing.ui.head kick="Berita & kegiatan" title="Kabar terbaru dari kampus" />
            <div class="news rv">
                <article class="nf">
                    @if (!empty($f['img']))
                        <div class="nf-cover">
                            <img src="{{ $f['img'] }}" alt="{{ $f['t'] ?? '' }}" loading="lazy"
                                onerror="this.style.display='none'">
                        </div>
                    @endif
                    <div class="nf-body">
                        <span class="tag">{{ $f['j'] ?? 'Berita' }}</span>
                        <h3>{{ $f['t'] ?? '' }}</h3>
                        <time>{{ $f['tgl'] ?? '' }}</time>
                    </div>
                </article>
                <div class="nl">
                    @foreach (array_slice($items, 1) as $n)
                        <article>
                            @if (!empty($n['img']))
                                <div class="nl-thumb">
                                    <img src="{{ $n['img'] }}" alt="{{ $n['t'] ?? '' }}" loading="lazy"
                                        onerror="this.style.display='none'">
                                </div>
                            @endif
                            <div class="nl-body">
                                <span class="tag">{{ $n['j'] ?? 'Berita' }}</span>
                                <h3>{{ $n['t'] ?? '' }}</h3>
                                <time>{{ $n['tgl'] ?? '' }}</time>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
