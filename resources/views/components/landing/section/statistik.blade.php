@props(['items' => config('landing.home.statistik')])
<section class="stat">
    <div class="wrap rv">
        @foreach ($items as $s)
            <div><b><span
                        data-to="{{ $s[0] }}">{{ number_format($s[0], 0, ',', '.') }}</span>{{ $s[1] }}</b><small>{{ $s[2] }}</small>
            </div>
        @endforeach
    </div>
</section>
