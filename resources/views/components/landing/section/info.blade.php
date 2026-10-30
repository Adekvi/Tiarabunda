@props(['items' => config('landing.home.info')])
@php($n = count($items))
@if ($n)
    <section class="info" id="info" aria-roledescription="carousel" aria-label="Info terkini">
        <div class="info-track">
            @foreach ($items as $i => $it)
                @php($src = asset('landing/' . $it['img']))
                <article class="info-slide {{ $i === 0 ? 'is-active' : '' }}" role="group" aria-roledescription="slide"
                    aria-label="{{ $i + 1 }} dari {{ $n }}"
                    @if ($i) aria-hidden="true" inert @endif>

                    <img class="info-bg" src="{{ $src }}" alt="" aria-hidden="true" loading="lazy"
                        decoding="async">

                    <div class="wrap info-in">
                        <div class="info-txt">
                            <p class="kick">Info terkini</p>
                            <span class="info-tag">{{ $it['k'] }}</span>
                            <h2>{{ $it['t'] }}</h2>
                            @isset($it['d'])
                                <p class="info-d">{{ $it['d'] }}</p>
                            @endisset
                            <time>{{ $it['tgl'] }}</time>
                            <div class="row">
                                <a class="btn" href="{{ $src }}" target="_blank" rel="noopener">Lihat gambar
                                    penuh <x-landing.ui.arrow /></a>
                                <a class="btn ghost w" href="{{ $src }}" download>Unduh</a>
                            </div>
                        </div>

                        <a class="info-poster" href="{{ $src }}" target="_blank" rel="noopener"
                            aria-label="Buka gambar: {{ $it['t'] }}">
                            <img src="{{ $src }}" alt="{{ $it['t'] }}" loading="lazy" decoding="async"
                                width="800" height="1120" onerror="this.remove()">
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        @if ($n > 1)
            <div class="wrap info-ctl">
                <div class="info-dots">
                    @foreach ($items as $i => $it)
                        <button type="button" class="{{ $i === 0 ? 'on' : '' }}"
                            @if ($i === 0) aria-current="true" @endif
                            aria-label="Ke slide {{ $i + 1 }}: {{ $it['t'] }}"><i></i></button>
                    @endforeach
                </div>
                <span class="info-count"><b id="info-cur">01</b> / {{ str_pad($n, 2, '0', STR_PAD_LEFT) }}</span>
                <div class="info-btns">
                    <button type="button" class="info-b" id="info-prev" aria-label="Slide sebelumnya">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 6l-6 6 6 6" />
                        </svg>
                    </button>
                    <button type="button" class="info-b info-tog" id="info-toggle" aria-label="Jeda slide otomatis">
                        <svg class="i-pause" viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                            <path d="M7 5h3.5v14H7zM13.5 5H17v14h-3.5z" />
                        </svg>
                        <svg class="i-play" viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </button>
                    <button type="button" class="info-b" id="info-next" aria-label="Slide berikutnya">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 6l6 6-6 6" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </section>
@endif
