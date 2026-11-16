@props(['highlight' => null, 'items' => []])

@php
    $items = collect($items);

    $getIcon = function ($ikon) {
        return match ($ikon) {
            'users'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            'star'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
            'lightbulb'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1.25.45 2.39 1.5 3.5.76.76 1.23 1.52 1.41 2.5"/></svg>',
            'heart'
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
            default
                => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
        };
    };

    $getColor = function ($warna) {
        return match ($warna) {
            'green' => 'kms__icon--green',
            'orange' => 'kms__icon--orange',
            'red' => 'kms__icon--red',
            'purple' => 'kms__icon--purple',
            default => 'kms__icon--blue',
        };
    };
@endphp

<section class="kms">
    <div class="wrap kms__wrap">

        <div class="kms__intro rv">
            <p class="kms__eyebrow">Organisasi & Kegiatan</p>
            <h2 class="kms__title">Kemahasiswaan</h2>
            <p class="kms__lead">
                Wadah pengembangan diri, minat, bakat, dan soft skill mahasiswa melalui berbagai organisasi dan kegiatan
                ekstrakurikuler.
            </p>
        </div>

        @if ($highlight)
            <div class="kms__highlight rv">
                <div class="kms__hl-icon">
                    {!! $getIcon(data_get($highlight, 'data_tambahan.ikon', 'users')) !!}
                </div>
                <div class="kms__hl-content">
                    <h3>{{ data_get($highlight, 'judhul') }}</h3>
                    <div class="kms__hl-text prose">
                        {!! data_get($highlight, 'isi') !!}
                    </div>
                </div>
            </div>
        @endif

        @if ($items->isNotEmpty())
            <div class="kms__grid">
                @foreach ($items as $item)
                    <div class="kms__card rv">
                        <div class="kms__card-icon {{ $getColor(data_get($item, 'data_tambahan.warna')) }}">
                            {!! $getIcon(data_get($item, 'data_tambahan.ikon')) !!}
                        </div>
                        <h3 class="kms__card-title">{{ data_get($item, 'judhul') }}</h3>
                        <div class="kms__card-desc prose">
                            {!! data_get($item, 'isi') !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="kms__cta rv">
            <div>
                <h3>Ingin bergabung dengan organisasi?</h3>
                <p>Daftarkan diri Anda pada masa pengenalan kehidupan kampus (PKKMB) atau hubungi bagian kemahasiswaan.
                </p>
            </div>
            <a class="btn" href="/kontak">Hubungi Kami &rarr;</a>
        </div>

    </div>
</section>
