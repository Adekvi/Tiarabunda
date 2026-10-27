@props(['items' => config('landing.home.mitra')])
<section class="mitra" aria-label="Mitra">
    <div class="track">
        @foreach ([0, 1] as $_)
            @foreach ($items as $m)
                <span>{{ $m }}</span>
            @endforeach
        @endforeach
    </div>
</section>
