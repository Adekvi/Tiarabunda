{{-- components/landing/section/fasilitas.blade.php --}}
@props(['items' => []])

@if (count($items))
    <section class="sec alt" id="fasilitas">
        <div class="wrap">
            <x-landing.ui.head kick="Fasilitas" title="Ruang belajar yang mendukung praktik" />
        </div>
        <div class="gal rv" aria-label="Galeri fasilitas">
            <div class="gal-track">
                @foreach ([false, true] as $dup)
                    @foreach ($items as $f)
                        <a href="{{ url('/layanan/fasilitas/' . $f['tandha']) }}" class="tile"
                            @if ($dup) aria-hidden="true" tabindex="-1" @endif>
                            @if (!empty($f['img']))
                                <img src="{{ $f['img'] }}" alt="{{ $dup ? '' : $f['nama'] }}" width="720"
                                    height="480" loading="lazy" decoding="async" onerror="this.remove()">
                            @else
                                <div class="tile-placeholder" aria-hidden="true"></div>
                            @endif
                            <figcaption>{{ $f['nama'] }}</figcaption>
                        </a>
                    @endforeach
                @endforeach
            </div>
        </div>
        <div class="wrap" style="display:flex;justify-content:center;margin-top:2rem">
            <a href="{{ url('/layanan/fasilitas') }}" class="btn-outline"
                style="display:inline-flex;align-items:center;gap:.5rem;
              padding:.75rem 1.5rem;border-radius:999px;
              font-weight:600;text-decoration:none;
              transition:transform .2s ease, box-shadow .2s ease">
                Lihat semua fasilitas
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </a>
        </div>
    </section>
@endif
