@props(['highlight' => null, 'items' => []])

@php
    $items = collect($items);

    $getIcon = function ($ikon) {
        return match ($ikon) {
            'briefcase'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>',
            'tool'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
            'trending-up'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>',
            'handshake'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 17a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2z"/><path d="M18 17a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-4"/><path d="M6 17a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h4"/></svg>',
            default
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
        };
    };

    $getColor = function ($warna) {
        return match ($warna) {
            'green' => 'kr__icon--green',
            'orange' => 'kr__icon--orange',
            'red' => 'kr__icon--red',
            'purple' => 'kr__icon--purple',
            default => 'kr__icon--blue',
        };
    };
@endphp

<section class="kr">
    <div class="wrap kr__wrap">

        <div class="kr__intro rv">
            <p class="kr__eyebrow">Pengembangan Karier</p>
            <h2 class="kr__title">Pusat Karier</h2>
            <p class="kr__lead">
                Membantu mahasiswa dan lulusan untuk mempersiapkan diri, mengembangkan keterampilan, dan menyalurkan
                potensi menuju dunia kerja profesional.
            </p>
        </div>

        @if ($highlight)
            <div class="kr__highlight rv">
                <div class="kr__hl-icon">
                    {!! $getIcon(data_get($highlight, 'data_tambahan.ikon', 'briefcase')) !!}
                </div>
                <div class="kr__hl-content">
                    <h3>{{ data_get($highlight, 'judhul') }}</h3>
                    <div class="kr__hl-text prose">
                        {!! data_get($highlight, 'isi') !!}
                    </div>
                </div>
            </div>
        @endif

        @if ($items->isNotEmpty())
            <div class="kr__grid">
                @foreach ($items as $item)
                    <div class="kr__card rv">
                        <div class="kr__card-icon {{ $getColor(data_get($item, 'data_tambahan.warna')) }}">
                            {!! $getIcon(data_get($item, 'data_tambahan.ikon')) !!}
                        </div>
                        <h3 class="kr__card-title">{{ data_get($item, 'judhul') }}</h3>
                        <div class="kr__card-desc prose">
                            {!! data_get($item, 'isi') !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="kr__cta rv">
            <div>
                <h3>Siap Memulai Karier Anda?</h3>
                <p>Konsultasikan masa depan Anda, persiapkan CV terbaik, dan temukan peluang kerja bersama kami.</p>
            </div>
            <a class="btn" href="/kontak">Konsultasi Karier &rarr;</a>
        </div>

    </div>
</section>
