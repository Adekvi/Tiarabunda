@props(['items' => config('landing.home.fasilitas')])
<section class="sec alt" id="fasilitas">
    <div class="wrap"><x-landing.ui.head kick="Fasilitas" title="Ruang belajar yang mendukung praktik" /></div>
    <div class="gal rv" aria-label="Galeri fasilitas">
        <div class="gal-track">
            @foreach ([false, true] as $dup)
                @foreach ($items as $f)
                    <figure class="tile" @if ($dup) aria-hidden="true" @endif><img
                            src="{{ asset('landing/' . $f[1]) }}" alt="{{ $dup ? '' : $f[0] }}" loading="lazy"
                            onerror="this.remove()">
                        <figcaption>{{ $f[0] }}</figcaption>
                    </figure>
                @endforeach
            @endforeach
        </div>
    </div>
</section>
