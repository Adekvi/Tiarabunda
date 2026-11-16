@props(['highlight' => null, 'items' => []])

@php
    $items = collect($items);

    // Helper untuk memilih ikon berdasarkan data_tambahan
    $getIcon = function ($ikon) {
        return match ($ikon) {
            'clock'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
            'layers'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>',
            'shield-alert'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
            default
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
        };
    };

    $getColor = function ($warna) {
        return match ($warna) {
            'green' => 'prs__icon--green',
            'orange' => 'prs__icon--orange',
            'red' => 'prs__icon--red',
            default => 'prs__icon--blue',
        };
    };
@endphp

<section class="prs">
    <div class="wrap prs__wrap">

        <div class="prs__intro rv">
            <p class="prs__eyebrow">Layanan Akademik</p>
            <h2 class="prs__title">Perpustakaan</h2>
            <p class="prs__lead">
                Pusat sumber belajar yang menyediakan koleksi literatur, jurnal, dan ruang baca nyaman untuk mendukung
                riset dan perkuliahan Anda.
            </p>
        </div>

        {{-- Highlight Box (Jika ada) --}}
        @if ($highlight)
            <div class="prs__highlight rv">
                <div class="prs__hl-icon">
                    {!! $getIcon(data_get($highlight, 'data_tambahan.ikon', 'book-open')) !!}
                </div>
                <div class="prs__hl-content">
                    <h3>{{ data_get($highlight, 'judhul') }}</h3>
                    <div class="prs__hl-text prose">
                        {!! data_get($highlight, 'isi') !!}
                    </div>
                </div>
            </div>
        @endif

        {{-- Grid Card untuk item lainnya --}}
        @if ($items->isNotEmpty())
            <div class="prs__grid">
                @foreach ($items as $item)
                    <div class="prs__card rv">
                        <div class="prs__card-icon {{ $getColor(data_get($item, 'data_tambahan.warna')) }}">
                            {!! $getIcon(data_get($item, 'data_tambahan.ikon')) !!}
                        </div>
                        <h3 class="prs__card-title">{{ data_get($item, 'judhul') }}</h3>
                        <div class="prs__card-desc prose">
                            {!! data_get($item, 'isi') !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- CTA --}}
        <div class="prs__cta rv">
            <div>
                <h3>Butuh bantuan mencari referensi?</h3>
                <p>Hubungi pustakawan kami atau kunjungi langsung ruang layanan sirkulasi.</p>
            </div>
            <a class="btn" href="/kontak">Hubungi Kami &rarr;</a>
        </div>

    </div>
</section>
