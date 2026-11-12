@props(['item' => null])

@php
    $judhul = data_get($item, 'judhul');
    $isi = data_get($item, 'isi', '');
    $kronologi = data_get($item, 'kronologi', []);

    if (is_string($kronologi)) {
        $kronologi = json_decode($kronologi, true) ?: [];
    }
    if ($kronologi instanceof \Illuminate\Support\Collection) {
        $kronologi = $kronologi->all();
    }
    if (!is_array($kronologi)) {
        $kronologi = [];
    }

    $sampul = data_get($item, 'path_sampul');
    $sampulUrl = $sampul
        ? (str_starts_with((string) $sampul, 'http')
            ? $sampul
            : asset('storage/' . ltrim((string) $sampul, '/')))
        : null;

    $paragraphs = [];
    $asHtml = false;
    if (is_string($isi) && $isi !== '') {
        if (str_contains($isi, '<p') || str_contains($isi, '<br')) {
            $asHtml = true;
        } else {
            $paragraphs = preg_split('/\n\s*\n/', trim($isi)) ?: [trim($isi)];
        }
    }
@endphp

<section class="sej">
    <div class="wrap sej__wrap">
        @if ($sampulUrl)
            <figure class="sej__cover rv">
                <img src="{{ $sampulUrl }}" alt="{{ $judhul ?: 'Sampul sejarah' }}" width="1200" height="520"
                    loading="eager" decoding="async">
                <figcaption class="sej__cover-shade" aria-hidden="true"></figcaption>
            </figure>
        @endif

        <div class="sej__intro rv">
            @if ($judhul)
                <h2 class="sej__title">{{ $judhul }}</h2>
            @endif
            <div class="sej__body">
                @if ($asHtml)
                    {!! $isi !!}
                @else
                    @foreach ($paragraphs as $p)
                        <p>{{ $p }}</p>
                    @endforeach
                @endif
            </div>
        </div>

        @if (count($kronologi) > 0)
            <div class="sej__kron rv">
                <div class="sej__kron-head">
                    <p class="sej__eyebrow">Kronologi</p>
                    <h3>Perjalanan institusi</h3>
                </div>
                <ol class="sej__timeline">
                    @foreach ($kronologi as $i => $row)
                        @php
                            $tahun = data_get($row, 'tahun', data_get($row, 'k'));
                            $judulK = data_get($row, 'judhul', data_get($row, 't'));
                            $isiK = data_get($row, 'isi', data_get($row, 'd'));
                        @endphp
                        <li class="sej__item" style="--i: {{ $i }}">
                            <div class="sej__marker" aria-hidden="true">
                                <span>{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="sej__card">
                                @if ($tahun)
                                    <span class="sej__year">{{ $tahun }}</span>
                                @endif
                                @if ($judulK)
                                    <h4 class="sej__item-title">{{ $judulK }}</h4>
                                @endif
                                @if ($isiK)
                                    <p class="sej__item-text">{{ $isiK }}</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        @endif
    </div>
</section>
