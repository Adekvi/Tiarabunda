@php
    $blocks = $page['blocks'] ?? [];
    $sejarah = $sejarah ?? null;
    $visimisi = $visimisi ?? null;
    $program = $program ?? null;
    $pmbJalur = $pmbJalur ?? null;
    $beasiswaList = $beasiswaList ?? null;
    $akreditasiInstitusi = $akreditasiInstitusi ?? null;
@endphp

<x-landing.layout.app :title="$page['t'] . ' — ' . config('landing.site.nama')" :description="$page['sub']">
    <x-landing.layout.topbar />
    <x-landing.layout.navbar />

    <main id="main">
        <x-landing.section.pagehero :menu="$menu" :page="$page" />

        @if ($sejarah)
            <x-landing.section.profil.sejarah :item="$sejarah" />
        @elseif ($visimisi)
            <x-landing.section.profil.visimisi :data="$visimisi" />
        @elseif ($program)
            <x-landing.section.pendidikan.program :fakultas="$program" />
        @elseif (isset($pmbJalur) && $pmbJalur)
            <x-landing.section.pendidikan.pmb :pmb-jalur="$pmbJalur" :selected-jalur="$selectedJalur ?? null" />
        @elseif ($beasiswaList)
            <x-landing.section.pendidikan.beasiswa :beasiswa-list="$beasiswaList" :selected-beasiswa="$selectedBeasiswa ?? null" />
        @elseif ($akreditasiInstitusi)
            <x-landing.section.pendidikan.akreditasi :institusi="$akreditasiInstitusi" :prodi="$akreditasiProdi ?? []" />
        @else
            <section class="sec page-body">
                <div class="wrap page-wrap">
                    <div class="page-main prose page-main--full">
                        @forelse ($blocks as $i => $b)
                            @php
                                $type = $b['type'] ?? 'text';
                                $anchor = 'bagian-' . ($i + 1);
                            @endphp
                            @switch($type)
                                @case('text')
                                    <x-landing.section.richtext :block="$b" :anchor="$anchor" />
                                @break

                                @case('cards')
                                    <x-landing.section.block.block-card :block="$b" :anchor="$anchor" />
                                @break

                                @case('timeline')
                                    <x-landing.section.block.block-timeline :block="$b" :anchor="$anchor" />
                                @break

                                @case('notice')
                                    <x-landing.section.block.block-notice :block="$b" :anchor="$anchor" />
                                @break

                                @case('split')
                                    <x-landing.section.block.block-split :block="$b" :anchor="$anchor" />
                                @break

                                @case('prodi')
                                    <x-landing.section.prodi />
                                @break

                                @case('jalur')
                                    <x-landing.section.jalur />
                                @break

                                @case('faq')
                                    <x-landing.section.faq />
                                @break

                                @case('berita')
                                    <x-landing.section.berita />
                                @break

                                @case('fasilitas')
                                    <x-landing.section.fasilitas />
                                @break

                                @default
                                    <x-landing.section.richtext :block="$b" :anchor="$anchor" />
                            @endswitch
                            @empty
                                <article class="page-empty rv">
                                    <h2>Konten segera tersedia</h2>
                                    <p>Halaman <strong>{{ $page['t'] }}</strong> sedang disiapkan.</p>
                                    <a class="btn" href="/">Kembali ke beranda</a>
                                </article>
                            @endforelse
                        </div>
                    </div>
                </section>
            @endif

            <x-landing.section.cta />
        </main>

        <x-landing.layout.footer />
    </x-landing.layout.app>
