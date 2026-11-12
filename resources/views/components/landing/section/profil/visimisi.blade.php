@props(['data' => []])

@php
    // Ambil data pertama saja untuk Visi dan Misi (karena biasanya hanya ada 1 judul besar)
    $visi = collect(data_get($data, 'visi', []))->first();
    $misi = collect(data_get($data, 'misi', []))->first();
    $lain = collect(data_get($data, 'lain', []));
@endphp

<section class="vm">
    <div class="wrap vm__wrap">

        {{-- VISI --}}
        @if ($visi)
            <article class="vm__visi rv">
                <p class="vm__eyebrow">{{ data_get($visi, 'judhul') ?: 'Visi' }}</p>
                @if (data_get($visi, 'isi'))
                    <blockquote class="vm__quote">
                        <p>{{ data_get($visi, 'isi') }}</p>
                    </blockquote>
                @endif
            </article>
        @endif

        {{-- MISI --}}
        @if ($misi)
            @php
                $list = data_get($misi, 'dhaptar', []);
                if (is_string($list)) {
                    $list = json_decode($list, true) ?: [];
                }
                if (!is_array($list)) {
                    $list = [];
                }
            @endphp
            <article class="vm__misi rv">
                <div class="vm__head">
                    <p class="vm__eyebrow">{{ data_get($misi, 'judhul') ?: 'Misi' }}</p>
                    @if (data_get($misi, 'isi'))
                        <p class="vm__lead">{{ data_get($misi, 'isi') }}</p>
                    @endif
                </div>
                @if (count($list) > 0)
                    <ol class="vm__list">
                        @foreach ($list as $i => $text)
                            <li style="--i: {{ $i }}">
                                <span class="vm__num"
                                    aria-hidden="true">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="vm__text">{{ $text }}</span>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </article>
        @endif

        {{-- BAGIAN LAIN (nilai, tujuan, dll.) --}}
        @foreach ($lain as $row)
            @php
                $list = data_get($row, 'dhaptar', []);
                if (is_string($list)) {
                    $list = json_decode($list, true) ?: [];
                }
                if (!is_array($list)) {
                    $list = [];
                }
            @endphp
            <article class="vm__extra rv">
                <div class="vm__head">
                    @if (data_get($row, 'judhul'))
                        <h2 class="vm__title">{{ data_get($row, 'judhul') }}</h2>
                    @endif
                    @if (data_get($row, 'isi'))
                        <p class="vm__lead">{{ data_get($row, 'isi') }}</p>
                    @endif
                </div>
                @if (count($list) > 0)
                    <div class="vm__cards">
                        @foreach ($list as $i => $text)
                            <div class="vm__card" style="--i: {{ $i }}">
                                <span class="vm__card-k">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <p class="vm__card-t">{{ $text }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </article>
        @endforeach

    </div>
</section>
