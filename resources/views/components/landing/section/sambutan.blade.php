@props(['r' => config('landing.home.rektor')])
<section class="sec sam">
    <div class="wrap sam-in rv">
        <div class="ava">
            @if (!empty($r['direktur']))
                <img src="{{ asset('landing/assets/img/' . $r['direktur']) }}" width="128" height="128"
                    alt="{{ $r['nama'] }}" loading="lazy" />
            @else
                {{ mb_substr($r['nama'], 0, 1) }}
            @endif
        </div>
        <div>
            <p class="kick">Sambutan pimpinan</p>
            <blockquote>“{{ $r['kutipan'] }}”</blockquote>
            <p class="who"><b>{{ $r['nama'] }}</b><br>{{ $r['jabatan'] }}</p>
        </div>
    </div>
</section>
