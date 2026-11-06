@props(['r' => null])

@if ($r)
    <section class="sec sam">
        <div class="wrap sam-in rv">
            <div class="ava">
                @if (!empty($r['foto_url']))
                    {{-- Bungkus gambar dengan div agar object-fit bekerja sempurna --}}
                    <img src="{{ $r['foto_url'] }}" alt="{{ $r['nama'] }}" loading="lazy" />
                @else
                    {{-- Placeholder jika tidak ada foto --}}
                    <span class="ava-initial">{{ mb_substr($r['nama'], 0, 1) }}</span>
                @endif
            </div>
            <div class="sam-body">
                <p class="kick">Sambutan pimpinan</p>
                <blockquote>“{{ $r['kutipan'] }}”</blockquote>
                <p class="who"><b>{{ $r['nama'] }}</b><br>{{ $r['jabatan'] }}</p>
            </div>
        </div>
    </section>
@endif
