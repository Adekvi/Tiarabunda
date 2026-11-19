@props(['tree' => []])

@php
    $roots = collect($tree);
    $getInitials = function (?string $name): string {
        $clean = preg_replace('/[^a-zA-Z\s]/', '', (string) $name);
        $letters = collect(explode(' ', $clean))
            ->filter()
            ->take(2)
            ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
            ->implode('');

        return $letters ?: 'UTB';
    };
@endphp

@push('css')
    <link rel="stylesheet" href="{{ asset('landing/menu/profil/struktur/str.css') }}">
@endpush

<section class="str">
    <div class="wrap str__wrap">

        {{-- Header Pengantar --}}
        <header class="str__intro rv">
            <p class="str__eyebrow">Tata Kelola & Organisasi</p>
            <h2 class="str__title">Struktur Organisasi {{ config('landing.site.nama') }}</h2>
            <p class="str__lead">
                Susunan pimpinan universitas, fakultas, dan unit pelaksana akademik dalam menyelenggarakan tata kelola
                pendidikan vokasi yang transparan dan akuntabel.
            </p>
        </header>

        @if ($roots->isNotEmpty())
            <div class="str__chart">
                @foreach ($roots as $root)
                    @php
                        $level2 = collect($root['children'] ?? []);
                        $level3 = $level2->flatMap(fn($child) => $child['children'] ?? [])->values();
                    @endphp

                    {{-- LEVEL 1: Pimpinan Utama --}}
                    <div class="str__tier str__tier--root rv">
                        <article class="str__card str__card--root">
                            <div class="str__avatar">
                                @if (!empty($root['foto_url']))
                                    <img src="{{ $root['foto_url'] }}" alt="{{ $root['jeneng'] }}" loading="lazy">
                                @else
                                    <span>{{ $getInitials($root['jeneng'] ?? '') }}</span>
                                @endif
                            </div>
                            <div class="str__info">
                                <span class="str__badge str__badge--gold">{{ $root['judhul'] }}</span>
                                <h3 class="str__name">{{ $root['jeneng'] }}</h3>
                                <small class="str__inst">{{ config('landing.site.nama') }}</small>
                            </div>
                        </article>
                    </div>

                    {{-- Garis Penghubung Vertikal --}}
                    @if ($level2->isNotEmpty())
                        <div class="str__stem" aria-hidden="true"></div>

                        {{-- LEVEL 2: Wakil Pimpinan --}}
                        <div class="str__tier str__tier--exec rv">
                            <div class="str__exec-grid" style="--cols: {{ min($level2->count(), 4) }}">
                                @foreach ($level2 as $exec)
                                    <div class="str__branch">
                                        <article class="str__card str__card--exec">
                                            <div class="str__avatar">
                                                @if (!empty($exec['foto_url']))
                                                    <img src="{{ $exec['foto_url'] }}" alt="{{ $exec['jeneng'] }}"
                                                        loading="lazy">
                                                @else
                                                    <span>{{ $getInitials($exec['jeneng'] ?? '') }}</span>
                                                @endif
                                            </div>
                                            <div class="str__info">
                                                <span class="str__badge">{{ $exec['judhul'] }}</span>
                                                <h4 class="str__name">{{ $exec['jeneng'] }}</h4>
                                            </div>
                                        </article>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- LEVEL 3: Dekan Fakultas & Kepala Lembaga --}}
                    @if ($level3->isNotEmpty())
                        <div class="str__stem str__stem--short" aria-hidden="true"></div>

                        <div class="str__tier str__tier--units rv">
                            <div class="str__units-head">
                                <span>Pimpinan Fakultas & Unit Lembaga</span>
                            </div>

                            <div class="str__units-grid">
                                @foreach ($level3 as $unit)
                                    <article class="str__card str__card--unit">
                                        <div class="str__avatar str__avatar--sm">
                                            @if (!empty($unit['foto_url']))
                                                <img src="{{ $unit['foto_url'] }}" alt="{{ $unit['jeneng'] }}"
                                                    loading="lazy">
                                            @else
                                                <span>{{ $getInitials($unit['jeneng'] ?? '') }}</span>
                                            @endif
                                        </div>
                                        <div class="str__info">
                                            <span class="str__unit-role">{{ $unit['judhul'] }}</span>
                                            <h5 class="str__unit-name">{{ $unit['jeneng'] }}</h5>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="str__empty rv">
                <h3>Bagan struktur sedang disiapkan</h3>
                <p>Data struktur organisasi akan tampil setelah diperbarui oleh administrator.</p>
            </div>
        @endif

    </div>
</section>
